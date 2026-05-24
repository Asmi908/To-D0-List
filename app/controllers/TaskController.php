<?php
namespace App\controllers;
 
use App\core\BaseController;
use App\models\Task;
use App\models\Category;
use App\models\Deadline;
use App\models\Note;
 
class TaskController extends BaseController
{
    private Task     $task;
    private Category $category;
    private Deadline $deadline;
    private Note     $note;
    private int      $userId;
 
    public function __construct()
    {
        parent::__construct();
        $this->task     = new Task();
        $this->category = new Category();
        $this->deadline = new Deadline();
        $this->note     = new Note();
        $this->userId   = $this->requireAuth();
    }
 
    /**
     * List tasks (optionally filtered by status).
     */
    public function index()
    {
        $page       = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage    = 10; // Number of tasks per page
        $status     = $_GET['filter_status'] ?? '';
        $tasks      = $status
            ? $this->task->getByStatus($this->userId, $status, $page, $perPage)
            : $this->task->getAll($this->userId, $page, $perPage);
        $categories = $this->category->getAllCategories($this->userId);
 
        error_log("Tasks fetched for user_id {$this->userId}: " . print_r($tasks, true));
        $this->loadView('task/index', [
            'tasks'         => $tasks,
            'categories'    => $categories,
            'filter_status' => $status,
            'page'          => $page,
            'perPage'       => $perPage
        ]);
    }
 
    /**
     * Show “Add New Task” form or handle its submission.
     */
    public function addTask()
    {
        // 1) On every request (GET or POST), check if there's a preselected category via ?id=#
        $prefillCategoryId = isset($_GET['id']) ? (int) $_GET['id'] : null;
 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 2) On POST, determine which category_id to use:
            //    a) If the form submitted a category_id, use that.
            //    b) Otherwise, fall back to the prefillCategoryId.
            $postedCategory = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
            $categoryId = $postedCategory
                ? (int) $postedCategory
                : $prefillCategoryId;
 
            $data = [
                'user_id'           => $this->userId,
                'task_title'        => trim(filter_input(INPUT_POST, 'task_title', FILTER_DEFAULT) ?? ''),
                'category_id'       => $categoryId,
                'task_status'       => trim(filter_input(INPUT_POST, 'task_status', FILTER_DEFAULT) ?? ''),
                'task_is_important' => isset($_POST['task_is_important']) ? 1 : 0,
            ];
            error_log("AddTask POST data: " . print_r($data, true));
 
            // 3) Validate required fields
            $errors = [];
            if (empty($data['task_title']))   $errors[] = "Task Title is required.";
            if (empty($data['category_id']))  $errors[] = "Category must be selected.";
            if (empty($data['task_status']))  $errors[] = "Status must be selected.";
 
            if (!empty($errors)) {
                $error      = implode(' ', $errors);
                $categories = $this->category->getAllCategories($this->userId);
                $this->loadView('task/addTask', [
                    'categories'        => $categories,
                    'error'             => $error,
                    'prefillCategoryId' => $prefillCategoryId
                ]);
                return;
            }
 
            // 4) Insert into tasks table
            $taskId = $this->task->add($data);
            error_log("Task add attempt, returned ID: $taskId");
 
            if ($taskId) {
                // Redirect to the Task Detail page
                $this->redirect('?url=task/viewFullTask/' . $taskId);
            } else {
                $error      = "Failed to add task.";
                $categories = $this->category->getAllCategories($this->userId);
                $this->loadView('task/addTask', [
                    'categories'        => $categories,
                    'error'             => $error,
                    'prefillCategoryId' => $prefillCategoryId
                ]);
                return;
            }
        }
 
        // 5) If GET, simply show the form and pass the optional prefillCategoryId
        $categories = $this->category->getAllCategories($this->userId);
        $this->loadView('task/addTask', [
            'categories'        => $categories,
            'prefillCategoryId' => $prefillCategoryId
        ]);
    }
 
    /**
     * Show “Edit Task” form or handle its submission.
     */
    public function editTask($taskId)
    {
        // First, ensure the task belongs to this user
        $existingTask = $this->task->getById($taskId);
        if (!$existingTask || (int)$existingTask['user_id'] !== $this->userId) {
            $this->loadView('task/error', ['message' => 'Task not found or access denied.']);
            return;
        }
 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect fields that exist in tasks table
            $data = [
                'user_id'           => $this->userId,
                'task_title'        => trim(filter_input(INPUT_POST, 'task_title', FILTER_DEFAULT) ?? ''),
                'category_id'       => filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT),
                'task_status'       => trim(filter_input(INPUT_POST, 'task_status', FILTER_DEFAULT) ?? ''),
                'task_is_important' => isset($_POST['task_is_important']) ? 1 : 0,
            ];
            error_log("EditTask POST data: " . print_r($data, true));
 
            // Validate required fields
            $errors = [];
            if (empty($data['task_title']))   $errors[] = "Task Title is required.";
            if (empty($data['category_id']))  $errors[] = "Category must be selected.";
            if (empty($data['task_status']))  $errors[] = "Status must be selected.";
 
            if (!empty($errors)) {
                $error      = implode(' ', $errors);
                $categories = $this->category->getAllCategories($this->userId);
                $this->loadView('task/editTask', [
                    'task'       => $existingTask,
                    'categories' => $categories,
                    'error'      => $error
                ]);
                return;
            }
 
            // Perform the update
            if ($this->task->update($taskId, $data)) {
                $this->redirect('?url=task/viewFullTask/' . $taskId);
            } else {
                $error      = "Failed to update task.";
                $categories = $this->category->getAllCategories($this->userId);
                $this->loadView('task/editTask', [
                    'task'       => $existingTask,
                    'categories' => $categories,
                    'error'      => $error
                ]);
                return;
            }
        }
 
        // GET: show the “Edit Task” form pre-filled
        $categories = $this->category->getAllCategories($this->userId);
        $this->loadView('task/editTask', [
            'task'       => $existingTask,
            'categories' => $categories
        ]);
    }
 
    /**
     * Delete a task.
     */
    public function deleteTask($taskId)
    {
        if ($this->task->delete($taskId)) {
            $this->redirect('?url=task');
        } else {
            $this->loadView('task/error', ['message' => 'Failed to delete task.']);
        }
    }
 
    /**
     * Show the Task Detail page, including any deadlines and notes.
     */
    public function viewFullTask($taskId)
    {
        // 1) Fetch the task itself (and ensure it belongs to this user)
        $task = $this->task->getById($taskId);
        if (!$task || (int)$task['user_id'] !== $this->userId) {
            $this->loadView('task/error', ['message' => 'Task not found or access denied.']);
            return;
        }
 
        // 2) (Optional) Fetch the deadline from a separate deadlines table, if you use it
        $deadline = $this->deadline->getByTaskId($taskId);
 
        // 3) Fetch all notes that belong to this task & current user (from notes table)
        $notes = $this->note->getNotesByTaskId($taskId, $this->userId);
 
        // 4) Load the “Task Detail” view, passing:
        //    • task data
        //    • deadline (if any)
        //    • notes array
        //    • taskId (so the inline note form knows which task ID to use)
        $this->loadView('task/viewTask', [
            'task'     => $task,
            'deadline' => $deadline,
            'notes'    => $notes,
            'taskId'   => $taskId
        ]);
    }
}
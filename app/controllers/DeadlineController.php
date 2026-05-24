<?php
namespace App\controllers;
use App\core\BaseController;
use App\models\Deadline;
use App\models\Task;
 
class DeadlineController extends BaseController
{
    private $deadlineModel;
    private $taskModel;
 
    public function __construct()
    {
        parent::__construct();
        $this->deadlineModel = new Deadline();
        $this->taskModel = new Task();
    }
 
    public function handleRequest()
    {
        $urlParts = explode('/', trim($_GET['url'] ?? '', '/'));
        $action = $urlParts[1] ?? 'index';
        $id = $urlParts[2] ?? null;
 
        switch ($action) {
            case 'index':
                return $this->index();
            case 'add':
                return $this->add();
            case 'store':
                return $this->store();
            case 'list':
                return $this->list();
            case 'edit':
                return $this->edit($id);
            case 'delete':
                return $this->delete($id);
            default:
                http_response_code(404);
                return ['view' => 'error/404', 'data' => ['message' => 'Action not found']];
        }
    }
 
    private function index()
    {
        $user_id = $this->requireAuth();
        $deadlines = $this->deadlineModel->getAll();
        return [
            'view' => 'deadline/home',
            'data' => ['deadlines' => $deadlines]
        ];
    }
 
    private function add()
    {
        $user_id = $this->requireAuth();
        $tasks = $this->taskModel->getAll($user_id); // Pass user_id
        return [
            'view' => 'deadline/addDeadline',
            'data' => ['tasks' => $tasks, 'user_id' => $user_id]
        ];
    }
 
  private function store()
{
    $user_id = $this->requireAuth();
 
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $tasks = $this->taskModel->getAll($user_id);
        return [
            'view' => 'deadline/addDeadline',
            'data' => [
                'tasks' => $tasks,
                'user_id' => $user_id
            ]
        ];
    }
 
    $data = [
        'user_id' => $user_id,
        'task_id' => $_POST['task_id'] ?? null,
        'description' => trim($_POST['description'] ?? ''),
        'due_date' => $_POST['due_date'] ?? '',
        'status' => $_POST['status'] ?? 'Pending',
        'priority' => $_POST['priority'] ?? 'Medium'
    ];
 
    $errors = $this->validateDeadlineData($data);
 
    if (empty($errors)) {
        try {
            if ($this->deadlineModel->add($data)) {
                // ✅ Success: show success page with a button to home
                return [
                    'view' => 'deadline/success',
                    'data' => []
                ];
            } else {
                $tasks = $this->taskModel->getAll($user_id);
                return [
                    'view' => 'deadline/addDeadline',
                    'data' => [
                        'error' => 'Error adding deadline.',
                        'tasks' => $tasks,
                        'user_id' => $user_id
                    ]
                ];
            }
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
            $tasks = $this->taskModel->getAll($user_id);
            return [
                'view' => 'deadline/addDeadline',
                'data' => [
                    'error' => 'Database error occurred.',
                    'tasks' => $tasks,
                    'user_id' => $user_id
                ]
            ];
        }
    } else {
        $tasks = $this->taskModel->getAll($user_id);
        return [
            'view' => 'deadline/addDeadline',
            'data' => [
                'error' => implode(' ', $errors),
                'tasks' => $tasks,
                'user_id' => $user_id
            ]
        ];
    }
}
 
 
    private function list()
    {
        $user_id = $this->requireAuth();
        $deadlines = $this->deadlineModel->getAll();
        return [
            'view' => 'deadline/listDeadline',
            'data' => ['deadlines' => $deadlines]
        ];
    }
 
    private function edit($id = null)
    {
        $user_id = $this->requireAuth();
        $deadline_id = $id ? (int)$id : (isset($_GET['id']) ? (int)$_GET['id'] : null);
 
        if (!$deadline_id) {
            return [
                'view' => 'deadline/editDeadline',
                'data' => ['error' => 'No deadline selected.']
            ];
        }
 
        $deadline = $this->deadlineModel->getById($deadline_id);
        $tasks = $this->taskModel->getAll($user_id); // Pass user_id
 
        if (!$deadline) {
            return [
                'view' => 'deadline/editDeadline',
                'data' => [
                    'error' => 'Deadline not found.',
                    'tasks' => $tasks
                ]
            ];
        }
 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'task_id' => $_POST['task_id'] ?? null,
                'description' => trim($_POST['description'] ?? ''),
                'due_date' => $_POST['due_date'] ?? '',
                'status' => $_POST['status'] ?? 'Pending',
                'priority' => $_POST['priority'] ?? 'Medium'
            ];
 
            $errors = $this->validateDeadlineData($data);
            if (empty($errors)) {
                try {
                    if ($this->deadlineModel->update($deadline_id, $data)) {
                        $this->redirect('?url=deadline/edit/' . $deadline_id . '&success=1');
                    } else {
                        return [
                            'view' => 'deadline/editDeadline',
                            'data' => [
                                'error' => 'Error updating deadline.',
                                'deadline' => $deadline,
                                'tasks' => $tasks,
                                'user_id' => $user_id
                            ]
                        ];
                    }
                } catch (PDOException $e) {
                    error_log("DB Error: " . $e->getMessage());
                    return [
                        'view' => 'deadline/editDeadline',
                        'data' => [
                            'error' => 'Database error occurred.',
                            'deadline' => $deadline,
                            'tasks' => $tasks,
                            'user_id' => $user_id
                        ]
                    ];
                }
            } else {
                return [
                    'view' => 'deadline/editDeadline',
                    'data' => [
                        'error' => implode(' ', $errors),
                        'deadline' => $deadline,
                        'tasks' => $tasks,
                        'user_id' => $user_id
                    ]
                ];
            }
        }
 
        return [
            'view' => 'deadline/editDeadline',
            'data' => [
                'deadline' => $deadline,
                'tasks' => $tasks,
                'user_id' => $user_id
            ]
        ];
    }
 
    private function delete($id = null)
    {
        $user_id = $this->requireAuth();
        $deadline_id = $id ? (int)$id : (isset($_GET['id']) ? (int)$_GET['id'] : null);
 
        if ($deadline_id) {
            try {
                if ($this->deadlineModel->delete($deadline_id)) {
                    $this->redirect('?url=deadline/delete&success=1');
                }
            } catch (PDOException $e) {
                error_log("DB Error: " . $e->getMessage());
            }
        }
 
        $deadlines = $this->deadlineModel->getAll();
        return [
            'view' => 'deadline/deleteDeadline',
            'data' => [
                'deadlines' => $deadlines,
                'success' => isset($_GET['success']),
                'error' => $deadline_id ? 'Error deleting deadline.' : null
            ]
        ];
    }
 
    private function validateDeadlineData($data)
    {
        $errors = [];
        if (empty($data['task_id']) || !is_numeric($data['task_id'])) {
            $errors[] = "Valid Task ID is required.";
        }
        if (empty($data['description'])) {
            $errors[] = "Description is required.";
        }
        if (empty($data['due_date']) || !strtotime($data['due_date'])) {
            $errors[] = "Valid due date is required.";
        }
        if (!in_array($data['status'], ['Pending', 'Completed'])) {
            $errors[] = "Invalid status.";
        }
        if (!in_array($data['priority'], ['Low', 'Medium', 'High'])) {
            $errors[] = "Invalid priority.";
        }
        return $errors;
    }
}
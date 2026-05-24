<?php
 
namespace App\controllers;
 
use App\core\BaseController;
use App\models\Note;
 
class NoteController extends BaseController
{
    private Note $note;
 
    public function __construct()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
       
        $this->note = new Note();
       
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            $this->redirect('?url=auth/login'); // Adjust to your login route
        }
    }
 
    /**
     * Check if user is logged in
     */
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
 
    /**
     * Get current user ID from session
     */
    private function getUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }
 
    public function handleRequest()
    {
        $action = $_GET['action'] ?? 'list';
        $noteId = $_GET['id'] ?? null;
        $taskId = $_GET['task_id'] ?? null;
        $searchTerm = $_GET['search'] ?? '';
 
        switch ($action) {
            case 'view':
                return $this->viewNote($noteId);
 
            case 'create':
                return $this->createNote($taskId);
 
            case 'update':
                return $this->update($noteId);
 
            case 'delete':
                return $this->deleteNote($noteId);
 
            case 'restore':
                return $this->restoreNote($noteId);
 
            case 'recycle_bin':
                return $this->showRecycleBin();
 
            default:
                return $this->listNotes($taskId, $searchTerm);
        }
    }
 
    private function viewNote(?string $noteId): array
    {
        if (!$noteId) {
            $this->redirect('?url=note');
        }
 
        $userId = $this->getUserId();
        $note = $this->note->getNoteById($noteId, $userId);
 
        if (!$note) {
            // Note not found or doesn't belong to user
            $this->redirect('?url=note');
        }
 
        return [
            'view' => 'notes/note_detail',
            'data' => ['note' => $note]
        ];
    }
 
    private function createNote(?string $taskId): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['task_id'] ?? null;
            $content = trim($_POST['content'] ?? '');
            $userId = $this->getUserId();
 
            if ($taskId && $content) {
                if ($this->note->createNote($taskId, $content, $userId)) {
                    // Success - redirect to task view
                    $this->redirect('?url=task/viewFullTask/' . $taskId);
                } else {
                    // Handle creation error
                    $error = "Failed to create note. Please try again.";
                    return [
                        'view' => 'notes/note_form',
                        'data' => [
                            'action' => 'create',
                            'error' => $error,
                            'task_id' => $taskId
                        ]
                    ];
                }
            } else {
                // Validation error
                $error = "Please provide both task ID and content.";
                return [
                    'view' => 'notes/note_form',
                    'data' => [
                        'action' => 'create',
                        'error' => $error,
                        'task_id' => $taskId
                    ]
                ];
            }
        }
 
        return [
            'view' => 'notes/note_form',
            'data' => [
                'action' => 'create',
                'task_id' => $taskId
            ]
        ];
    }
 
   public function update(?string $noteId): array
{
    if (!$noteId) {
        $this->redirect('?url=note');
    }
 
    $userId = $this->getUserId();
    $note   = $this->note->getNoteById($noteId, $userId);
 
    if (!$note) {
        $this->redirect('?url=note');
    }
 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $content = trim($_POST['content'] ?? '');
 
        if ($content === '') {
            $error = "Content cannot be empty.";
        } elseif ($this->note->updateNote($noteId, $content, $userId)) {
            // SUCCESS: redirect immediately
            $this->redirect('?url=note&action=view&id=' . $noteId);
        } else {
            $error = "Failed to update note. Please try again.";
        }
 
        // On validation or DB error, re‐fetch and re‐render edit form
        $note = $this->note->getNoteById($noteId, $userId);
        return [
            'view' => 'notes/note_edit_form',
            'data' => [
                'note'  => $note,
                'error' => $error
            ]
        ];
    }
 
    // GET: show the edit form
    return [
        'view' => 'notes/note_edit_form',
        'data' => [
            'note' => $note
        ]
    ];
}
 
 
    private function deleteNote(?string $noteId): void
    {
        if ($noteId) {
            $userId = $this->getUserId();
            $this->note->deleteNote($noteId, $userId);
        }
 
        $this->redirect('?url=note');
    }
 
    private function restoreNote(?string $noteId): void
    {
        if ($noteId) {
            $userId = $this->getUserId();
            if ($this->note->restoreNote($noteId, $userId)) {
                // Successfully restored - redirect to view the note
                $this->redirect('?url=note&action=view&id=' . $noteId);
            }
        }
 
        // If restore failed or no note ID, redirect to recycle bin
        $this->redirect('?url=note&action=recycle_bin');
    }
 
    private function showRecycleBin(): array
    {
        $userId = $this->getUserId();
        $deletedNotes = $this->note->getDeletedNotes($userId);
 
        return [
            'view' => 'notes/deleted_note_view',
            'data' => ['deletedNotes' => $deletedNotes]
        ];
    }
 
    private function listNotes(?string $taskId, string $searchTerm): array
    {
        $userId = $this->getUserId();
 
        if (!empty($searchTerm)) {
            $notes = $this->note->searchNotes($searchTerm, $userId);
            $title = 'Search Results for "' . htmlspecialchars($searchTerm) . '"';
        } elseif ($taskId) {
            $notes = $this->note->getNotesByTaskId($taskId, $userId);
            $title = 'Notes for Task #' . htmlspecialchars($taskId);
        } else {
            $notes = $this->note->getAllNotesByUser($userId);
            $title = 'All Notes';
        }
 
        return [
            'view' => 'notes/notes_view',
            'data' => [
                'notes' => $notes,
                'searchTerm' => $searchTerm,
                'title' => $title,
                'taskId' => $taskId
            ]
        ];
    }
}
 
 
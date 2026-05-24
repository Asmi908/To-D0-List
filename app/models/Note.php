<?php
 
namespace App\models;
 
use PDO;
use PDOException;
use App\config\Database;
 
class Note
{
    private $db;
    private $conn;
 
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
 
 
   
 
    // Creates a new note for the given task and user.
    // Ensures the note is associated with the logged-in user and marked as active (not deleted).
    public function createNote($taskId, $content, $userId)
    {
        try {
            $query = "INSERT INTO tb_note (task_id, user_id, content, ncreated_date, is_deleted)
                  VALUES (:task_id, :user_id, :content, NOW(), 0)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':task_id', $taskId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':content', $content);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Create Error: " . $e->getMessage();
            return false;
        }
    }
 
 
    // Retrieves all active (non-deleted) notes belonging to the logged-in user.
    // Includes related task titles and orders results by most recent.
    public function getAllNotesByUser($userId)
    {
        try {
            $query = "SELECT n.*, t.task_title
                  FROM tb_note n
                  LEFT JOIN tasks t ON n.task_id = t.task_id
                  WHERE n.is_deleted = 0 AND n.user_id = :user_id
                  ORDER BY n.ncreated_date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
 
 
 
    // Retrieves all active notes for a given task ID that belong to the logged-in user.
    // Includes related task title information and sorts by creation date descending.
    public function getNotesByTaskId($taskId, $userId)
    {
        try {
            $query = "SELECT n.*, t.task_title
                  FROM tb_note n
                  LEFT JOIN tasks t ON n.task_id = t.task_id
                  WHERE n.task_id = :task_id
                    AND t.user_id = :user_id
                    AND n.is_deleted = 0
                  ORDER BY n.ncreated_date DESC";
 
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':task_id', $taskId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
 
 
 
    // Retrieves a single active note by its ID, only if it belongs to the logged-in user.
    // Includes the associated task title for display context.
    public function getNoteById($noteId, $userId)
    {
        try {
            $query = "SELECT n.*, t.task_title
                  FROM tb_note n
                  LEFT JOIN tasks t ON n.task_id = t.task_id
                  WHERE n.note_id = :note_id
                  AND n.user_id = :user_id
                  AND n.is_deleted = 0";
 
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':note_id', $noteId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
 
 
 public function updateNote($noteId, $newContent, $userId)
{
    try {
        $sql = "UPDATE tb_note
                   SET content = :content
                 WHERE note_id = :note_id
                   AND user_id = :user_id
                   AND is_deleted = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':content',  $newContent, PDO::PARAM_STR);
        $stmt->bindValue(':note_id',  (int)$noteId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id',  (int)$userId, PDO::PARAM_INT);
 
        return $stmt->execute();
    } catch (PDOException $e) {
        // In production, log instead of echoing. For now you can do:
        error_log("Update Error: " . $e->getMessage());
        return false;
    }
}
 
 
    // Soft deletes a note (marks it as deleted) if it belongs to the logged-in user.
    // Prevents unauthorized deletion of other users' notes.
    public function deleteNote($noteId, $userId)
    {
        try {
            $query = "UPDATE tb_note
                  SET is_deleted = 1
                  WHERE note_id = :note_id AND user_id = :user_id AND is_deleted = 0";
 
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':note_id', $noteId);
            $stmt->bindParam(':user_id', $userId);
 
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Delete Error: " . $e->getMessage();
            return false;
        }
    }
 
 
    // Restores a soft-deleted note if it belongs to the logged-in user.
    // Useful for recovering accidentally deleted notes.
    public function restoreNote($noteId, $userId)
    {
        try {
            $query = "UPDATE tb_note
                  SET is_deleted = 0
                  WHERE note_id = :note_id AND user_id = :user_id AND is_deleted = 1";
 
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':note_id', $noteId);
            $stmt->bindParam(':user_id', $userId);
 
            return $stmt->execute() && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Restore Error: " . $e->getMessage();
            return false;
        }
    }
 
 
    // Searches active (non-deleted) notes owned by the logged-in user for a given keyword.
    // Matches content partially and includes task titles for clarity.
    public function searchNotes($searchTerm, $userId)
    {
        try {
            $searchTerm = "%$searchTerm%";
            $query = "SELECT n.*, t.task_title
                  FROM tb_note n
                  LEFT JOIN tasks t ON n.task_id = t.task_id
                  WHERE n.is_deleted = 0
                  AND n.user_id = :user_id
                  AND n.content LIKE :search_term
                  ORDER BY n.ncreated_date DESC";
 
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':search_term', $searchTerm);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Search Error: " . $e->getMessage();
            return [];
        }
    }
    // Retrieves all notes that have been soft-deleted by the logged-in user.
    // Allows users to view and optionally restore their deleted notes.
    public function getDeletedNotes($userId)
    {
        try {
            $query = "SELECT n.*, t.task_title
                  FROM tb_note n
                  LEFT JOIN tasks t ON n.task_id = t.task_id
                  WHERE n.is_deleted = 1 AND n.user_id = :user_id
                  ORDER BY n.ncreated_date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
 
 
 
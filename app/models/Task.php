<?php
namespace App\models;
 
use PDO;
use PDOException;
use App\config\Database;
 
class Task
{
    private $db;
    private $conn;
 
    public function __construct()
    {
        $this->db   = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
 
    // Fetch all tasks for a user with pagination
    public function getAll(int $userId, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->conn->prepare("
            SELECT
                t.*,
                c.category_name
            FROM tasks t
            JOIN categories c ON t.category_id = c.category_id
            WHERE t.user_id = :user_id
            ORDER BY t.task_id ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    // Fetch by status with pagination
    public function getByStatus(int $userId, string $status, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->conn->prepare("
            SELECT
                t.*,
                c.category_name
            FROM tasks t
            JOIN categories c ON t.category_id = c.category_id
            WHERE t.user_id = :user_id
              AND t.task_status = :status
            ORDER BY t.task_id ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    // Fetch one task
    public function getById(int $taskId): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                t.*,
                c.category_name
            FROM tasks t
            JOIN categories c ON t.category_id = c.category_id
            WHERE t.task_id = :id
        ");
        $stmt->execute(['id' => $taskId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }
 
    // Insert a task
    public function add(array $data)
    {
        try {
            $sql = "
                INSERT INTO tasks
                    (user_id, category_id, task_title, task_status, task_is_important)
                VALUES
                    (:user_id, :category_id, :task_title, :task_status, :task_is_important)
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':user_id'           => $data['user_id'],
                ':category_id'       => $data['category_id'],
                ':task_title'        => $data['task_title'],
                ':task_status'       => $data['task_status'],
                ':task_is_important' => $data['task_is_important'],
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Task->add() error: " . $e->getMessage());
            return false;
        }
    }
 
    // Update a task
    public function update(int $taskId, array $data): bool
    {
        try {
            $sql = "
                UPDATE tasks
                SET
                    task_title        = :task_title,
                    category_id       = :category_id,
                    task_status       = :task_status,
                    task_is_important = :task_is_important
                WHERE task_id = :task_id
                  AND user_id = :user_id
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':task_title'        => $data['task_title'],
                ':category_id'       => $data['category_id'],
                ':task_status'       => $data['task_status'],
                ':task_is_important' => $data['task_is_important'],
                ':task_id'           => $taskId,
                ':user_id'           => $data['user_id'],
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Task->update() error: " . $e->getMessage());
            return false;
        }
    }
 
    // Delete a task
    public function delete(int $taskId): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM tasks WHERE task_id = :id");
        return $stmt->execute(['id' => $taskId]);
    }
}
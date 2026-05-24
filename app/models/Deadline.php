<?php
namespace App\models;
use PDO;
use PDOException;
use App\config\Database;
 
class Deadline {
    private $db;
    private $conn;
 
    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
 
 
    // Get all deadlines
    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM deadlines ORDER BY due_date ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    // Get deadline by ID
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM deadlines WHERE deadline_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
    // Add a new deadline
    public function add($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO deadlines (user_id, task_id, description, due_date, status, priority)
            VALUES (:user_id, :task_id, :description, :due_date, :status, :priority)
        ");
 
        return $stmt->execute([
            'user_id'     => $data['user_id'],
            'task_id'     => $data['task_id'],
            'description' => $data['description'],
            'due_date'    => $data['due_date'],
            'status'      => $data['status'],
            'priority'    => $data['priority']
        ]);
    }
 
    // Update an existing deadline
    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("
            UPDATE deadlines
            SET task_id = :task_id,
                description = :description,
                due_date = :due_date,
                status = :status,
                priority = :priority
            WHERE deadline_id = :id
        ");
 
        return $stmt->execute([
            'task_id'     => $data['task_id'],
            'description' => $data['description'],
            'due_date'    => $data['due_date'],
            'status'      => $data['status'],
            'priority'    => $data['priority'],
            'id'          => $id
        ]);
    }
 
    // Delete a deadline
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM deadlines WHERE deadline_id = :id");
        return $stmt->execute(['id' => $id]);
    }
   
    public function getByTaskId($taskId)
{
    $stmt = $this->conn->prepare("SELECT * FROM deadlines WHERE task_id = :task_id LIMIT 1");
    $stmt->execute(['task_id' => $taskId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
 
}
 
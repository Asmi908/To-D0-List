<?php
// Category Model
// Handles all database operations for categories using PDO

namespace App\models;

require_once __DIR__ . '/../config/Database.php';

use App\config\Database;
use PDO;

class Category {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    public function getAllCategories($statusFilter = 'active', $searchTerm = '', $userId = null) {
        try {
            $query = "SELECT c.*, t.task_title 
                      FROM categories c 
                      LEFT JOIN tasks t ON c.category_id = t.category_id
                      WHERE c.is_active = 1";
            $params = [];
            
            // Filter by user_id if provided
            if ($userId) {
                $query .= " AND c.user_id = ?";
                $params[] = $userId;
            }
            
            // Filter by status
            if ($statusFilter === 'inactive') {
                $query .= " AND c.is_active = 0";
            }
            
            // Filter by name (case-insensitive)
            if (!empty($searchTerm)) {
                $query .= " AND LOWER(c.category_name) LIKE LOWER(?)";
                $params[] = "%$searchTerm%";
            }
            
            $query .= " ORDER BY c.created_date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error in getAllCategories: " . $e->getMessage());
            return [];
        }
    }

    public function getCategoryById($categoryId, $userId = null) {
        try {
            $query = "SELECT c.*, COALESCE(GROUP_CONCAT(t.task_title SEPARATOR ', '), '') as task_titles 
                      FROM categories c 
                      LEFT JOIN tasks t ON c.category_id = t.category_id AND t.user_id = :user_id
                      WHERE c.category_id = :category_id AND c.user_id = :user_id
                      GROUP BY c.category_id, c.category_name, c.category_description, c.is_active, c.created_date";
            $params = [
                ':category_id' => $categoryId,
                ':user_id' => $userId
            ];
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Database Error in getCategoryById: " . $e->getMessage());
            return null;
        }
    }

    public function create($name, $description, $user_id) {
        try {
            // Check if a category with the same name (case-insensitive) already exists for the user
            $query = "SELECT COUNT(*) FROM categories WHERE LOWER(category_name) = LOWER(?) AND user_id = ? AND is_active = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$name, $user_id]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                error_log("Duplicate category name attempted: Name=$name, UserID=$user_id");
                return false; // Category with this name already exists
            }

            // Proceed with category creation if no duplicate is found
            $query = "INSERT INTO categories (category_name, category_description, user_id, is_active, created_date) VALUES (?, ?, ?, 1, NOW())";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $description, $user_id]);
        } catch (PDOException $e) {
            error_log("Database Error in create: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $name, $description, $userId = null) {
        try {
            $query = "UPDATE categories SET category_name = ?, category_description = ? WHERE category_id = ?";
            $params = [$name, $description, $id];
            
            if ($userId) {
                $query .= " AND user_id = ?";
                $params[] = $userId;
            }
            
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Database Error in update: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id, $userId = null) {
        try {
            // Check if there are any tasks linked to the category
            $query = "SELECT COUNT(*) FROM tasks WHERE category_id = ?";
            $params = [$id];
            
            if ($userId) {
                $query .= " AND user_id = ?";
                $params[] = $userId;
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $taskCount = $stmt->fetchColumn();
            
            if ($taskCount > 0) {
                error_log("Cannot delete category: ID=$id, tasks exist");
                return false;
            }
    
            // Proceed with marking the category as inactive
            $query = "UPDATE categories SET is_active = 0 WHERE category_id = ?";
            $params = [$id];
            
            if ($userId) {
                $query .= " AND user_id = ?";
                $params[] = $userId;
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $rowCount = $stmt->rowCount();
            
            if ($rowCount > 0) {
                return true;
            } else {
                error_log("No rows affected in delete: ID=$id, UserID=$userId");
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database Error in delete: " . $e->getMessage());
            return false;
        }
    }
    public function getCategoryImage($category_name) {
        $normalized = strtolower(trim($category_name));
        $filename = preg_replace('/[^a-z0-9-_]/', '', $normalized); // sanitize
        $imagePath = "/TODOLISTAPP/public/css/categoryImages/{$filename}.jpg";
    
        // Server-side full path for existence check
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;
    
        if (file_exists($fullPath)) {
            return $imagePath;
        }
    
        return "/TODOLISTAPP/public/css/categoryImages/others.jpg";
    }

    public function getCategoryNames($userId = null) {
        try {
            $query = "SELECT DISTINCT category_name FROM categories WHERE is_active = 1";
            $params = [];
            
            if ($userId) {
                $query .= " AND user_id = ?";
                $params[] = $userId;
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Database Error in getCategoryNames: " . $e->getMessage());
            return [];
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
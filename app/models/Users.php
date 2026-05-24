<?php
 
namespace App\models;
 
use App\config\Database;
use PDO;
use PDOException;
 
class Users
{
    private PDO $conn;
 
    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }
 
    public function create(string $username, string $email, string $password): bool
    {
        try {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare(
                "INSERT INTO users (username, email, password, created_at, updated_at, is_active)
                 VALUES (:username, :email, :password, NOW(), NOW(), 1)"
            );
            return $stmt->execute([
                ':username' => $username,
                ':email'    => $email,
                ':password' => $hashed
            ]);
 
        } catch (PDOException $e) {
           
            return false;
       
        }
    }
 
    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT user_id, username, email, password FROM users WHERE email = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
    public function updatePassword($userId, $password): bool
    {
        try {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare(
                "UPDATE users SET password = :password, updated_at = NOW() WHERE user_id = :user_id"
            );
            return $stmt->execute([
                ':password' => $hashed,
                ':user_id' => $userId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
 
    public function deleteUser($userId): bool
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
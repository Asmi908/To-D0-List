<?php
// Database class for handling database operations
// Provides a PDO connection using config constants
namespace App\config;
 
use PDO;
use PDOException;
 
// Load DB constants from config file
require_once __DIR__ . '/config.php';
 
class Database {
    private static $instance = null;
    private $conn;
 
    protected function __construct() {
        try {
            $dsn = "mysql:host=" . \DB_HOST . ";port=" . \DB_PORT . ";dbname=" . \DB_NAME . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, DB_USER, DB_PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
 
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
 
    public function getConnection() {
        return $this->conn;
    }
}
 






    
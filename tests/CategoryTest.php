<?php

use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    protected $category;

    protected function setUp(): void
    {
        $this->category = new \App\models\Category();
        $this->category->getConnection()->exec("SET FOREIGN_KEY_CHECKS=0");
        $this->category->getConnection()->exec("TRUNCATE TABLE categories");
        $this->category->getConnection()->exec("TRUNCATE TABLE users");
        $this->category->getConnection()->exec("TRUNCATE TABLE tasks");
        $this->category->getConnection()->exec("SET FOREIGN_KEY_CHECKS=1");
        $this->category->getConnection()->exec("INSERT INTO users (user_id, username, email, password, created_at, updated_at,
        is_active) VALUES (1, 'testuser', 'testuser@example.com', 'hashedpassword123', NOW(), NOW(), 1)");
    }

    protected function tearDown(): void
    {
        $this->category->getConnection()->exec("SET FOREIGN_KEY_CHECKS=0");
        $this->category->getConnection()->exec("TRUNCATE TABLE categories");
        $this->category->getConnection()->exec("TRUNCATE TABLE users");
        $this->category->getConnection()->exec("TRUNCATE TABLE tasks");
        $this->category->getConnection()->exec("SET FOREIGN_KEY_CHECKS=1");
    }

    public function test_create_valid_category()
    {
        $result = $this->category->create("TestCat" . rand(), "Test Desc", 1);
        $this->assertTrue($result);
    }

    public function test_create_duplicate_category()
    {
        $name = "DupCat" . rand();
        $this->category->create($name, "Desc", 1);

        // Set up a temporary log file for error_log
        $logFile = sys_get_temp_dir() . '/phpunit_error.log';
        ini_set('error_log', $logFile);

        // Clear the log file if it exists
        if (file_exists($logFile)) {
            unlink($logFile);
        }

        // Execute the create method that should trigger the duplicate error
        $result = $this->category->create($name, "Desc", 1);

        // Read the log file contents
        $logOutput = file_exists($logFile) ? file_get_contents($logFile) : '';

        // Reset error_log to default
        ini_set('error_log', null);

        $this->assertFalse($result);
        $this->assertStringContainsString("Duplicate category name attempted: Name=$name, UserID=1", $logOutput);
    }

    public function test_get_category_by_id()
    {
        $name = "TestGet" . rand();
        $this->category->create($name, "Test Desc", 1);
        $stmt = $this->category->getConnection()->query("SELECT LAST_INSERT_ID() as id");
        $id = $stmt->fetch()['id'];
        $category = $this->category->getCategoryById($id, 1);
        $this->assertNotNull($category);
    }

    public function test_update_category()
    {
        $this->category->create("UpdateTest" . rand(), "Old Desc", 1);
        $stmt = $this->category->getConnection()->query("SELECT LAST_INSERT_ID() as id");
        $id = $stmt->fetch()['id'];
        $result = $this->category->update($id, "UpdatedTest", "New Desc", 1);
        $this->assertTrue($result);
    }

    public function test_delete_category()
    {
        $name = "DeleteTest" . rand();
        $this->category->create($name, "Test Desc", 1);
        $stmt = $this->category->getConnection()->query("SELECT LAST_INSERT_ID() as id");
        $id = $stmt->fetch()['id'];
        $result = $this->category->delete($id, 1);
        $this->assertTrue($result);
    }
}
<?php

use PHPUnit\Framework\TestCase;

class CategoryControllerTest extends TestCase
{
    protected $controller;
    protected $category;

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        ob_start();
        $_SESSION['user_id'] = 1;
        $this->controller = new \App\controllers\CategoryController();
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
        unset($_SESSION['user_id']);
        $this->category->getConnection()->exec("SET FOREIGN_KEY_CHECKS=0");
        $this->category->getConnection()->exec("TRUNCATE TABLE categories");
        $this->category->getConnection()->exec("TRUNCATE TABLE users");
        $this->category->getConnection()->exec("TRUNCATE TABLE tasks");
        $this->category->getConnection()->exec("SET FOREIGN_KEY_CHECKS=1");
        ob_end_clean();
    }

    public function test_handle_request_create()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['category_name'] = 'TestCreate' . rand();
        $_POST['category_description'] = 'Test Desc';
        $response = $this->controller->handleRequest();
        $this->assertArrayHasKey('view', $response);
        $this->assertEquals('category/index', $response['view']);
    }

    public function test_handle_request_edit()
    {
        $this->category->create("EditTest" . rand(), "Old Desc", 1);
        $stmt = $this->category->getConnection()->query("SELECT LAST_INSERT_ID() as id");
        $id = $stmt->fetch()['id'];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['action'] = 'edit';
        $_GET['id'] = $id;
        $_POST['category_name'] = 'EditedTest';
        $_POST['category_description'] = 'Edited Desc';
        $response = $this->controller->handleRequest();
        $this->assertArrayHasKey('view', $response);
        $this->assertEquals('category/index', $response['view']);
    }

    public function test_handle_request_delete()
    {
        $this->category->create("DeleteTest" . rand(), "Test Desc", 1);
        $stmt = $this->category->getConnection()->query("SELECT LAST_INSERT_ID() as id");
        $id = $stmt->fetch()['id'];
        $_GET['action'] = 'delete';
        $_GET['id'] = $id;
        $response = $this->controller->handleRequest();
        $this->assertArrayHasKey('view', $response);
        $this->assertEquals('category/index', $response['view']);
    }
}
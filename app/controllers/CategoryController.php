<?php
// Category Controller
// Manages the logic for category-related actions with a single handleRequest method

namespace App\controllers;

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Category.php';

use App\core\BaseController;
use App\models\Category;

class CategoryController extends BaseController {
    private $category;

    public function __construct() {
        parent::__construct();
        $this->category = new Category();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? 'list';
        $searchTerm = $_GET['search'] ?? '';
        $categoryId = $_GET['id'] ?? null;
        $statusFilter = $_GET['status'] ?? 'active';
        $userId = $this->requireAuth();
    
        if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['category_name'] ?? '';
            $description = $_POST['category_description'] ?? '';
            if (empty($name)) {
                return ['view' => 'category/createCategory', 'data' => ['error' => 'Category name is required.']];
            }
            // Verify user exists in the database
            $query = "SELECT COUNT(*) FROM users WHERE user_id = ? AND is_active = 1";
            $stmt = $this->category->getConnection()->prepare($query);
            $stmt->execute([$userId]);
            if ($stmt->fetchColumn() == 0) {
                error_log("User does not exist: UserID=$userId");
                return ['view' => 'category/createCategory', 'data' => ['error' => 'Invalid user. Please log in again.']];
            }
            if ($this->category->create($name, $description, $userId)) {
                $categories = $this->category->getAllCategories($statusFilter, $searchTerm, $userId);
                $categoryNames = $this->category->getCategoryNames($userId);
                $title = empty($searchTerm) ? 'All Categories' : 'Search Results for "' . htmlspecialchars($searchTerm) . '"';
                return ['view' => 'category/index', 'data' => [
                    'categories' => $categories,
                    'categoryNames' => $categoryNames,
                    'searchTerm' => $searchTerm,
                    'statusFilter' => $statusFilter,
                    'title' => $title
                ]];
            } else {
                $query = "SELECT COUNT(*) FROM categories WHERE LOWER(category_name) = LOWER(?) AND user_id = ? AND is_active = 1";
                $stmt = $this->category->getConnection()->prepare($query);
                $stmt->execute([$name, $userId]);
                $errorMessage = $stmt->fetchColumn() > 0 ? 'A category with this name already exists.' : 'Error creating category. Please try again.';
                error_log("Failed to create category: Name=$name, Description=$description, UserID=$userId, Error=$errorMessage");
                return ['view' => 'category/createCategory', 'data' => ['error' => $errorMessage]];
            }
        
        } elseif ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;
            $name = $_POST['category_name'] ?? '';
            $description = $_POST['category_description'] ?? '';
            if ($id && $this->category->update($id, $name, $description, $userId)) {
                $categories = $this->category->getAllCategories($statusFilter, $searchTerm, $userId);
                $categoryNames = $this->category->getCategoryNames($userId);
                $title = empty($searchTerm) ? 'All Categories' : 'Search Results for "' . htmlspecialchars($searchTerm) . '"';
                return ['view' => 'category/index', 'data' => [
                    'categories' => $categories,
                    'categoryNames' => $categoryNames,
                    'searchTerm' => $searchTerm,
                    'statusFilter' => $statusFilter,
                    'title' => $title
                ]];
            } else {
                error_log("Failed to update category: ID=$id, Name=$name, Description=$description");
                $category = $this->category->getCategoryById($id, $userId);
                return ['view' => 'category/updateCategory', 'data' => ['category' => $category, 'error' => 'Error updating category. Please try again.']];
            }
        } elseif ($action === 'delete' && $categoryId) {
            if ($this->category->delete($categoryId, $userId)) {
                $categories = $this->category->getAllCategories($statusFilter, $searchTerm, $userId);
                $categoryNames = $this->category->getCategoryNames($userId);
                $title = empty($searchTerm) ? 'All Categories' : 'Search Results for "' . htmlspecialchars($searchTerm) . '"';
                return ['view' => 'category/index', 'data' => [
                    'categories' => $categories,
                    'categoryNames' => $categoryNames,
                    'searchTerm' => $searchTerm,
                    'statusFilter' => $statusFilter,
                    'title' => $title
                ]];
            } else {
                error_log("Failed to delete category: ID=$categoryId");
                $categories = $this->category->getAllCategories($statusFilter, $searchTerm, $userId);
                $categoryNames = $this->category->getCategoryNames($userId);
                $title = empty($searchTerm) ? 'All Categories' : 'Search Results for "' . htmlspecialchars($searchTerm) . '"';
                return [
                    'view' => 'category/index',
                    'data' => [
                        'categories' => $categories,
                        'categoryNames' => $categoryNames,
                        'searchTerm' => $searchTerm,
                        'statusFilter' => $statusFilter,
                        'title' => $title,
                        'error' => 'Cannot delete category with linked tasks.'
                    ]
                ];
            }
        } elseif ($action === 'create') {
            return ['view' => 'category/createCategory', 'data' => []];
        } elseif ($action === 'edit' && $categoryId) {
            $category = $this->category->getCategoryById($categoryId, $userId);
            if ($category === null) {
                return ['view' => 'category/index', 'data' => []];
            }
            return ['view' => 'category/updateCategory', 'data' => ['category' => $category]];
        } elseif ($action === 'view' && $categoryId) {
            $category = $this->category->getCategoryById($categoryId, $userId);
            if ($category === null) {
                return ['view' => 'category/index', 'data' => []];
            }
            return ['view' => 'category/readCategory', 'data' => ['category' => $category]];
        } else {
            $categories = $this->category->getAllCategories($statusFilter, $searchTerm, $userId);
            $categoryNames = $this->category->getCategoryNames($userId);
            $title = empty($searchTerm) ? 'All Categories' : 'Search Results for "' . htmlspecialchars($searchTerm) . '"';
            return [
                'view' => 'category/index',
                'data' => [
                    'categories' => $categories,
                    'categoryNames' => $categoryNames,
                    'searchTerm' => $searchTerm,
                    'statusFilter' => $statusFilter,
                    'title' => $title
                ]
            ];
        }
    }
}
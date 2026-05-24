<?php
// Category List View
// Displays the list of categories in styled boxes with filters and management options
 
// Extract data from controller
$data = $data ?? [];
$categories = $data['categories'] ?? [];
$title = $data['title'] ?? 'All Categories';
$searchTerm = $data['searchTerm'] ?? '';
$statusFilter = $data['statusFilter'] ?? 'active';
$categoryNames = $data['categoryNames'] ?? [];
$error = $data['error'] ?? '';
$message = $data['message'] ?? ''; // Add message for feedback
 include __DIR__ . '/../includes/header.php'; 
require_once __DIR__ . '/../../models/Category.php';
if (empty($categoryNames)) {
    $categoryModel = new \App\models\Category();
    $categoryNames = $categoryModel->getCategoryNames();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Category Management - To-Do List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
        .category-box .card-img-top {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
</style>
</head>
<body class="bg-light">
<div class="container py-5">
<div class="text-center mb-5">
<h1 class="display-4 fw-bold text-dark"><?php echo htmlspecialchars($title); ?></h1>
<p class="lead text-muted">Organize your tasks with categories</p>
</div>
 
        <?php if ($error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if ($message): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
 
        <!-- Include the filter form -->
<?php include __DIR__ . '/filterCategory.php'; ?>
 
        <div class="text-center mb-4">
<a href="?url=category&action=create" class="btn btn-success btn-lg">Add New Category</a>
</div>
 
        <div class="row">
<?php if (empty($categories)): ?>
<div class="col-12 text-center">
<p class="text-muted">No categories found. Add a new category to get started!</p>
</div>
<?php else: ?>
<?php foreach ($categories as $row): ?>
<div class="col-md-4 mb-4">
<div class="category-box card shadow-sm h-100">
<img src="<?php echo htmlspecialchars((new \App\models\Category())->getCategoryImage($row['category_name'] ?? '')); ?>"
                                 class="card-img-top" alt="<?php echo htmlspecialchars($row['category_name'] ?? ''); ?>">
<div class="card-body">
<h5 class="card-title"><?php echo htmlspecialchars($row['category_name'] ?? ''); ?></h5>
<p class="card-text text-muted"><?php echo htmlspecialchars($row['category_description'] ?? 'No description'); ?></p>
<div class="btn-group">
<a href="?url=category&action=view&id=<?php echo $row['category_id']; ?>" class="btn btn-info btn-sm">View</a>
<a href="?url=category&action=edit&id=<?php echo $row['category_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="#" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['category_id']; ?>">Delete</a>
<a href="?url=task&action=create&id=<?php echo $row['category_id']; ?>" class="btn btn-primary btn-sm">Add Task</a>
</div>
</div>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>
 
    <!-- Custom Confirmation Modal for Category Deletion -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
                    Are you sure you want to delete this category?
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
</div>
</div>
</div>
</div>
 
    <!-- Custom Confirmation Modal for Account Deletion -->
<div class="modal fade" id="confirmDeleteAccountModal" tabindex="-1" aria-labelledby="confirmDeleteAccountModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="confirmDeleteAccountModalLabel">Confirm Delete Account</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
                    Are you sure you want to delete your account? This action cannot be undone and will also delete all your categories.
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<form action="?url=auth/deleteAccount" method="POST">
<button type="submit" class="btn btn-danger">Delete Account</button>
</form>
</div>
</div>
</div>
</div>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
        // Custom delete confirmation for categories
        document.addEventListener('DOMContentLoaded', () => {
            let deleteId = null;
            const modalElement = document.getElementById('confirmDeleteModal');
            const modal = new bootstrap.Modal(modalElement);
 
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    deleteId = button.getAttribute('data-id');
                    modal.show();
                });
            });
 
            document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
                if (deleteId) {
                    window.location.href = `?url=category&action=delete&id=${deleteId}`;
                }
                modal.hide();
            });
 
            modalElement.addEventListener('hidden.bs.modal', () => {
                deleteId = null;
            });
        });

    

</script>
</body>
<footer><button type="button" class="btn btn-danger btn-lg ms-2" data-bs-toggle="modal" data-bs-target="#confirmDeleteAccountModal">
                Delete Account
</button></footer>
</html>
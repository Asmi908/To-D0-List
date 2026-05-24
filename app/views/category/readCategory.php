<?php
// Read Category View
// Displays details of a specific category and its associated tasks

require_once __DIR__ . '/../../models/Category.php';

// Extract category data from controller
$data = $data ?? [];
$category = $data['category'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Category - To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <img src="<?php echo htmlspecialchars((new \App\models\Category())->getCategoryImage(strtolower($category['category_name'] ?? ''))); ?>"
             class="card-img-top"
             alt="<?php echo htmlspecialchars($category['category_name'] ?? 'Category'); ?>">

        <div class="card-body">
            <h2 class="card-title text-center mb-4"><?php echo htmlspecialchars($category['category_name'] ?? 'Untitled'); ?></h2>

            <p class="card-text"><strong>Description:</strong>
                <?php echo htmlspecialchars($category['category_description'] ?? 'No description'); ?>
            </p>

            <p class="card-text"><strong>Status:</strong>
                <?php echo isset($category['is_active']) && $category['is_active'] ? 'Active' : 'Inactive'; ?>
            </p>

            <p class="card-text"><strong>Created:</strong>
                <?php echo htmlspecialchars($category['created_date'] ?? 'Unknown'); ?>
            </p>

            <h5 class="mt-4">Associated Tasks:</h5>
<?php if (!empty($category['task_titles'])): ?>
    <ul class="list-group">
        <?php
        $tasks = explode(', ', $category['task_titles']);
        foreach ($tasks as $task) {
            if (!empty($task)) {
                echo '<li class="list-group-item">' . htmlspecialchars($task) . '</li>';
            }
        }
        ?>
    </ul>
<?php else: ?>
    <p class="text-muted">No tasks associated with this category.</p>
<?php endif; ?>

            <div class="text-center mt-4">
                <a href="?url=category&action=edit&id=<?php echo $category['category_id']; ?>" class="btn btn-warning me-2">Edit</a>
                <a href="#" class="btn btn-danger me-2 delete-btn" data-id="<?php echo $category['category_id']; ?>">Delete</a>
                <a href="?url=task&action=create&id=<?php echo $category['category_id']; ?>" class="btn btn-primary me-2">Add Task</a>
                <a href="?url=category" class="btn btn-outline-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Delete -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete this category?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let deleteId = null;
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

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
    });
</script>
</body>
</html>
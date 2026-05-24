<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
<?php
// Include the header
$headerPath = __DIR__ . '/../includes/header.php';
if (file_exists($headerPath)) {
    require $headerPath;
} else {
    echo '<p class="text-danger">Error: Header file not found at ' . htmlspecialchars($headerPath) . '</p>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 40px 20px; background-color: #f4f6f8; }
        .container { max-width: 800px; background-color: #fff; padding: 30px 40px; margin: auto; border-radius: 8px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 25px; }
        .task-item { border-bottom: 1px solid #e0e0e0; padding: 15px 0; }
        .task-item:last-child { border-bottom: none; }
        .add-task-btn { background-color: #3498db; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; }
        .add-task-btn:hover { background-color: #2980b9; }
        .action-btn { margin-right: 5px; }
        .pagination { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Task Management</h2>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="<?= BASE_URL ?>?url=task/addTask" class="add-task-btn">➕ Add Task</a>
            <form method="GET" action="<?= BASE_URL ?>?url=task/index" class="d-inline">
                <input type="hidden" name="url" value="task/index">
                <label for="filter_status" class="form-label me-2">Filter by Status:</label>
                <select name="filter_status" id="filter_status" class="form-select d-inline" style="width: auto;" onchange="this.form.submit()">
                    <option value="" <?= !isset($filter_status) || $filter_status === '' ? 'selected' : '' ?>>-- All --</option>
                    <option value="To-Do" <?= isset($filter_status) && $filter_status === 'To-Do' ? 'selected' : '' ?>>To-Do</option>
                    <option value="In Progress" <?= isset($filter_status) && $filter_status === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Completed" <?= isset($filter_status) && $filter_status === 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </form>
        </div>
        <?php if (isset($tasks) && !empty($tasks)): ?>
            <?php foreach ($tasks as $task): ?>
                <div class="task-item">
                    <h3><?= htmlspecialchars($task['task_title'] ?? 'N/A') ?></h3>
                    <p>Category: <?= htmlspecialchars($task['category_name'] ?? 'N/A') ?></p>
                    <p>Created: <?= htmlspecialchars($task['task_created_date'] ?? 'N/A') ?></p>
                    <p>Status: <?= htmlspecialchars($task['task_status'] ?? 'N/A') ?></p>
                    <p>Important: <?= isset($task['task_is_important']) && $task['task_is_important'] ? 'Yes' : 'No' ?></p>
                    <div>
                        <a href="<?= BASE_URL ?>?url=task/editTask/<?= $task['task_id'] ?>" class="btn btn-warning btn-sm action-btn">Edit</a>
                        <a href="<?= BASE_URL ?>?url=task/deleteTask/<?= $task['task_id'] ?>" class="btn btn-danger btn-sm action-btn" onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>
                        <a href="<?= BASE_URL ?>?url=task/viewFullTask/<?= $task['task_id'] ?>" class="btn btn-info btn-sm action-btn">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <!-- Pagination Links -->
            <nav class="pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= BASE_URL ?>?url=task/index&page=<?= $page - 1 ?><?= $filter_status ? '&filter_status=' . urlencode($filter_status) : '' ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= BASE_URL ?>?url=task/index&page=<?= $page + 1 ?><?= $filter_status ? '&filter_status=' . urlencode($filter_status) : '' ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php else: ?>
            <p>No tasks found.</p>
        <?php endif; ?>
        <?php
        // Include the footer
        $footerPath = __DIR__ . '/../includes/footer.php';
        if (file_exists($footerPath)) {
            require $footerPath;
        } else {
            echo '<p class="text-danger">Error: Footer file not found at ' . htmlspecialchars($footerPath) . '</p>';
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
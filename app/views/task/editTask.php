<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 40px 20px; background-color: #f4f6f8; }
        .container { max-width: 600px; background-color: #fff; padding: 30px 40px; margin: auto; border-radius: 8px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 25px; }
        .form-label { font-weight: 600; color: #34495e; }
        .form-control, .form-select { background-color: #fefefe; transition: border 0.3s; }
        .form-control:focus, .form-select:focus { border-color: #3498db; box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25); }
        .form-check { margin-top: 20px; }
        .btn-custom { padding: 10px 18px; font-size: 14px; }
        .btn-custom-primary { background-color: #27ae60; border-color: #27ae60; }
        .btn-custom-primary:hover { background-color: #1e8449; border-color: #1e8449; }
        .back-link { font-size: 14px; color: #7f8c8d; text-decoration: none; }
        .back-link:hover { color: #2c3e50; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Task</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>?url=task/editTask/<?= $task['task_id'] ?>" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="task_title" class="form-label">Task Title:</label>
                <input type="text" name="task_title" id="task_title" class="form-control" value="<?= htmlspecialchars($task['task_title'] ?? '') ?>" required>
                <div class="invalid-feedback">Please enter a task title.</div>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category:</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= isset($task['category_id']) && $cat['category_id'] == $task['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please select a category.</div>
            </div>
            <div class="mb-3">
                <label for="task_status" class="form-label">Status:</label>
                <select name="task_status" id="task_status" class="form-select" required>
                    <option value="To-Do" <?= isset($task['task_status']) && $task['task_status'] == 'To-Do' ? 'selected' : '' ?>>To-Do</option>
                    <option value="In Progress" <?= isset($task['task_status']) && $task['task_status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Completed" <?= isset($task['task_status']) && $task['task_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
                <div class="invalid-feedback">Please select a status.</div>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="task_is_important" id="task_is_important" class="form-check-input" <?= isset($task['task_is_important']) && $task['task_is_important'] ? 'checked' : '' ?>>
                <label for="task_is_important" class="form-check-label">Mark as Important</label>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" class="btn btn-custom btn-custom-primary">Update Task</button>
                <a href="<?= BASE_URL ?>?url=task" class="back-link">← Back to Task List</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
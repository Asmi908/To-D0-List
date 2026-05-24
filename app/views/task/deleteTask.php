<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 40px 20px; background-color: #f4f6f8; }
        .container { max-width: 600px; background-color: #fff; padding: 30px 40px; margin: auto; border-radius: 8px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .warning { font-weight: bold; color: #dc3545; margin-bottom: 20px; }
        .list-group-item { border: none; padding: 10px 0; }
        .btn-custom { padding: 10px 18px; font-size: 14px; }
        .btn-custom-danger { background-color: #dc3545; border-color: #dc3545; }
        .btn-custom-danger:hover { background-color: #c82333; border-color: #c82333; }
        .btn-custom-secondary { background-color: #6c757d; border-color: #6c757d; }
        .btn-custom-secondary:hover { background-color: #5a6268; border-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Confirm Delete</h2>
        <p class="warning">Are you sure you want to delete this task?</p>
        <ul class="list-group">
            <li class="list-group-item"><strong>Title:</strong> <?= htmlspecialchars($task['task_title']) ?></li>
            <li class="list-group-item"><strong>Due Date:</strong> <?= htmlspecialchars($task['task_due']) ?></li>
        </ul>
        <form method="POST" action="<?= BASE_URL ?>?url=task/deleteTask/<?= $task['task_id'] ?>">
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-custom btn-custom-danger">Yes, Delete</button>
                <a href="<?= BASE_URL ?>?url=task" class="btn btn-custom btn-custom-secondary">Cancel</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            padding: 20px;
            color: #333;
        }
        h2, h3 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .task-details, .notes-section, .deadline-section {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-custom {
            padding: 10px 18px;
            background-color: #3498db;
            border-color: #3498db;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 14px;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover {
            color: #2c3e50;
            text-decoration: underline;
        }
        .note-item, .deadline-item {
            border-bottom: 1px solid #e0e0e0;
            padding: 10px 0;
        }
        .note-item:last-child, .deadline-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="task-details">
        <h2>Task Details</h2>
        <p class="text-muted mb-3">View your task’s details, deadline, and notes below.</p>
        <p><strong>Title:</strong> <?= htmlspecialchars($task['task_title']) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($task['category_name']) ?></p>
        <p><strong>Created Date:</strong> <?= htmlspecialchars($task['task_created_date']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($task['task_status']) ?></p>
        <p><strong>Important:</strong> <?= $task['task_is_important'] ? 'Yes' : 'No' ?></p>
        <a href="<?= BASE_URL ?>?url=task" class="back-link">← Back to Task List</a>
    </div>
 
    <div class="deadline-section">
        <h3>Deadline</h3>
        <?php if ($deadline): ?>
            <div class="deadline-item">
                <p><strong>Description:</strong> <?= htmlspecialchars($deadline['description'] ?? 'N/A') ?></p>
                <p><strong>Due Date:</strong> <?= htmlspecialchars($deadline['due_date'] ?? 'N/A') ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($deadline['status'] ?? 'N/A') ?></p>
                <p><strong>Priority:</strong> <?= htmlspecialchars($deadline['priority'] ?? 'N/A') ?></p>
            </div>
        <?php else: ?>
            <p>No deadline set.</p>
        <?php endif; ?>
    </div>
 
    <div class="notes-section">
        <h3>Notes</h3>
        <?php if (!empty($notes)): ?>
            <?php foreach ($notes as $note): ?>
                <div class="note-item">
                    <p><?= htmlspecialchars($note['content']) ?></p>
                    <p><small>Created: <?= htmlspecialchars($note['created_at'] ?? 'N/A') ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No notes available.</p>
        <?php endif; ?>
    </div>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
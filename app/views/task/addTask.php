
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Task</title>
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
        .btn-custom-primary { background-color: #3498db; border-color: #3498db; }
        .btn-custom-primary:hover { background-color: #2980b9; border-color: #2980b9; }
        .back-link { font-size: 14px; color: #7f8c8d; text-decoration: none; }
        .back-link:hover { color: #2c3e50; text-decoration: underline; }
        .alert-success { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Task</h2>
        <?php
        // Handle form submission
        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task_title = trim($_POST['task_title'] ?? '');
            $category_id = trim($_POST['category_id'] ?? '');
            $task_status = trim($_POST['task_status'] ?? '');
            $task_is_important = isset($_POST['task_is_important']) ? 1 : 0;
 
            if (empty($task_title) || empty($category_id) || empty($task_status)) {
                $error = 'All fields are required.';
            } else {
                require_once __DIR__ . '/../models/Task.php';
                $taskModel = new Task();
                $data = [
                    'user_id' => $_SESSION['user_id'], // Assuming user is logged in
                    'task_title' => $task_title,
                    'category_id' => $category_id,
                    'task_status' => $task_status,
                    'task_is_important' => $task_is_important
                ];
                if ($taskModel->add($data)) {
                    $newTaskId = $taskModel->db->conn->insert_id; // Get the last inserted ID
                    $success = 'Task added successfully!';
                } else {
                    $error = 'Failed to add task. Please try again.';
                }
            }
        }
        ?>
 
        <?php if (isset($error) && !empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($success) && !empty($success)): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success) ?>
                <?php if ($newTaskId): ?>
                    <p>Task ID: <?= htmlspecialchars($newTaskId) ?></p>
                    <!-- Embed note and deadline forms with the new task_id -->
                    <hr>
                    <?php
                    $embed = true;
                    $taskId = $newTaskId;
                    $noteFormPath = __DIR__ . '/../notes/note_form.php';
                    if (file_exists($noteFormPath)) {
                        require $noteFormPath;
                    } else {
                        echo '<p class="text-danger">Error: Notes form not found at ' . htmlspecialchars($noteFormPath) . '</p>';
                    }
                    ?>
                    <hr>
                    <?php
                    $embed = true;
                    $taskId = $newTaskId;
                    $deadlineFormPath = __DIR__ . '/../deadlines/addDeadline.php'; // Corrected typo
                    error_log("Attempting to include deadline form at: $deadlineFormPath"); // Debug log
                    if (file_exists($deadlineFormPath)) {
                        require $deadlineFormPath;
                    } else {
                        echo '<p class="text-danger">Error: Deadline form not found at ' . htmlspecialchars($deadlineFormPath) . '. Please ensure addDeadline.php exists in app/views/deadlines/</p>';
                    }
                    ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (empty($success) || !isset($newTaskId)): ?>
            <div class="alert alert-warning mb-3" role="alert">
                Do not enter sensitive information in tasks.
            </div>
            <?php if (empty($categories)): ?>
                <div class="alert alert-warning" role="alert">
                    No categories found. <a href="<?= BASE_URL ?>?url=category/createCategory" class="text-primary">Add a category</a> first.
                </div>
            <?php endif; ?>
            <p class="text-muted mb-3">Enter task details below. Use categories to organize tasks and mark important ones for priority.</p>
            <form method="POST" action="<?= BASE_URL ?>?url=task/addTask" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="task_title" class="form-label">Task Title:</label>
                    <input type="text" name="task_title" id="task_title" class="form-control" value="<?= htmlspecialchars($_POST['task_title'] ?? '') ?>" required>
                    <div class="invalid-feedback">
                        Please enter a task title.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category:</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Please select a category.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="task_status" class="form-label">Status:</label>
                    <select name="task_status" id="task_status" class="form-select" required>
                        <option value="">-- Select Status --</option>
                        <option value="To-Do" <?= (isset($_POST['task_status']) && $_POST['task_status'] == 'To-Do') ? 'selected' : '' ?>>To-Do</option>
                        <option value="In Progress" <?= (isset($_POST['task_status']) && $_POST['task_status'] == 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                        <option value="Completed" <?= (isset($_POST['task_status']) && $_POST['task_status'] == 'Completed') ? 'selected' : '' ?>>Completed</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select a status.
                    </div>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="task_is_important" id="task_is_important" class="form-check-input" <?= isset($_POST['task_is_important']) ? 'checked' : '' ?>>
                    <label for="task_is_important" class="form-check-label">Mark as Important</label>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="submit" class="btn btn-custom btn-custom-primary">Save Task</button>
                    <a href="<?= BASE_URL ?>?url=task" class="back-link">← Back to Task List</a>
                </div>
            </form>
        <?php endif; ?>
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
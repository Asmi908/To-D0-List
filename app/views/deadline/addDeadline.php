<?php
$title = "Add Deadline";

// Database connection (adjust credentials)
$conn = new mysqli("localhost", "root", "", "todolist_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tasks for the dropdown
$sql_tasks = "SELECT task_id, task_title FROM tasks";
$result_tasks = $conn->query($sql_tasks);
$tasks = $result_tasks->fetch_all(MYSQLI_ASSOC);

// Handle success/error messages
$error = isset($_GET['error']) ? urldecode($_GET['error']) : null;
$success = isset($_GET['success']) ? true : null;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h3 {
            text-align: center;
        }
        .form-group { 
            margin: 15px auto;
            max-width: 600px;
        }
        .form-group label { display: block; margin-bottom: 6px; }
        .form-group input, .form-group select, .form-group textarea { 
            width: 100%; 
            padding: 8px;
            box-sizing: border-box;
        }
        button { 
            padding: 10px 20px; 
            background: #4CAF50; 
            color: #fff; 
            border: none; 
            border-radius: 4px;
            display: block;
            margin: 20px auto;
        }
        button:hover { background: #45a049; }
        a { 
            text-decoration: none; 
            color: #2196F3;
            display: inline-block;
            margin: 10px 0;
        }
        a:hover { text-decoration: underline; }
        #date-error { color: red; display: none; }
    </style>
</head>
<body>
    <div class="container">
        <a href="?url=deadline/index">Back to Home</a>
        <h3>Add Deadline</h3>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;">❌ <?php echo htmlspecialchars($error); ?></p>
        <?php elseif (isset($success)): ?>
            <p style="color: green; text-align: center;">✅ Deadline added successfully!</p>
        <?php endif; ?>

        <form action="?url=deadline/store" method="POST">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id ?? 1); ?>">
            <div class="form-group">
                <label for="task_id">Task</label>
                <select name="task_id" required>
                    <option value="">--Select Task--</option>
                    <?php foreach ($tasks as $task): ?>
                        <option value="<?php echo htmlspecialchars($task['task_id']); ?>">
                            <?php echo htmlspecialchars($task['task_id'] . ' - ' . $task['task_title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Enter description" required></textarea>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="datetime-local" id="due_date" name="due_date" required>
                <p id="date-error">❌ Date/time cannot be in the past.</p>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Pending" selected>Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <button type="submit">Submit</button>
            <a href="?url=deadline/index" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
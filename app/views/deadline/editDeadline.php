<?php
$title = "Edit Deadline";

// Database connection
$conn = new mysqli("localhost", "root", "", "todolist_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch specific deadline
$deadline = null;
if (isset($_GET['url']) && preg_match('/deadline\/edit\/(\d+)/', $_GET['url'], $matches)) {
    $deadline_id = $matches[1];
    $sql = "SELECT * FROM deadlines WHERE deadline_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $deadline_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $deadline = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all deadlines
$sql_all = "SELECT * FROM deadlines";
$result_all = $conn->query($sql_all);
$all_deadlines = $result_all->fetch_all(MYSQLI_ASSOC);

// Fetch tasks for dropdown
$sql_tasks = "SELECT task_id, task_title FROM tasks";
$result_tasks = $conn->query($sql_tasks);
$tasks = $result_tasks->fetch_all(MYSQLI_ASSOC);

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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2, h3 {
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
            margin: 20px auto;
            display: block;
        }
        button:hover { background: #45a049; }
        a { 
            text-decoration: none; 
            color: #2196F3;
            display: inline-block;
            margin: 10px 0;
        }
        a:hover { text-decoration: underline; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 10px; 
            text-align: center; 
        }
        th { background-color: #f4f4f4; }
        a.edit-link { 
            padding: 6px 12px; 
            background: #4CAF50; 
            color: #fff; 
            border-radius: 4px; 
        }
        a.edit-link:hover { background: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <a href="?url=deadline/index">← Back to Home</a>
        <h2>Edit Deadline</h2>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif (isset($_GET['success'])): ?>
            <p style="color: green; text-align: center;">✅ Deadline updated successfully!</p>
        <?php endif; ?>

        <?php if (isset($deadline)): ?>
        <form action="?url=deadline/edit/<?php echo htmlspecialchars($deadline['deadline_id']); ?>" method="post">
            <div class="form-group">
                <label for="user_id">User ID</label>
                <input type="number" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="task_id">Task</label>
                <select name="task_id" required>
                    <option value="">--Select Task--</option>
                    <?php foreach ($tasks as $task): ?>
                        <option value="<?php echo htmlspecialchars($task['task_id']); ?>" <?php echo $task['task_id'] == $deadline['task_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($task['task_id'] . ' - ' . $task['task_title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" rows="4" required><?php echo htmlspecialchars($deadline['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="datetime-local" name="due_date" value="<?php echo date('Y-m-d\TH:i', strtotime($deadline['due_date'])); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" required>
                    <option value="Pending" <?php echo $deadline['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Completed" <?php echo $deadline['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <div class="form-group">
                <label for="priority">Priority</label>
                <select name="priority" required>
                    <option value="Low" <?php echo $deadline['priority'] == 'Low' ? 'selected' : ''; ?>>Low</option>
                    <option value="Medium" <?php echo $deadline['priority'] == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                    <option value="High" <?php echo $deadline['priority'] == 'High' ? 'selected' : ''; ?>>High</option>
                </select>
            </div>
            <button type="submit">Update Deadline</button>
        </form>
        <?php else: ?>
            <p style="text-align: center;">No deadline selected for editing.</p>
        <?php endif; ?>

        <h3>All Deadlines</h3>
        <?php if (!empty($all_deadlines)): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Task ID</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($all_deadlines as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['deadline_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['task_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['priority']); ?></td>
                        <td><a class="edit-link" href="?url=deadline/edit/<?php echo htmlspecialchars($row['deadline_id']); ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No deadlines found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
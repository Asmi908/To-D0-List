<?php
$title = "Delete Deadlines";

$conn = new mysqli("localhost", "root", "", "todolist_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = false;
$error = null;

// Undo logic
if (isset($_GET['undo']) && is_numeric($_GET['undo'])) {
    $undo_id = $_GET['undo'];
    $restore_sql = "INSERT INTO deadlines SELECT * FROM temp_deleted_deadlines WHERE deadline_id = $undo_id";
    if ($conn->query($restore_sql)) {
        $conn->query("DELETE FROM temp_deleted_deadlines WHERE deadline_id = $undo_id");
        $success = true;
    } else {
        $error = "Failed to undo deletion.";
    }
}

// Fetch current deadlines
$deadlines = [];
$result = $conn->query("SELECT * FROM deadlines");
if ($result) {
    $deadlines = $result->fetch_all(MYSQLI_ASSOC);
}
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
        h2 {
            text-align: center;
        }
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
        th {
            background-color: #f4f4f4;
        }
        a.delete-link {
            padding: 6px 12px;
            background: #ff4444;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
        }
        a.delete-link:hover {
            background: #cc0000;
        }
        a {
            text-decoration: none;
            color: #2196F3;
            display: inline-block;
            margin: 10px 0;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="?url=deadline/index">← Back to Home</a>
        <h2>Delete Deadlines</h2>

        <?php if ($success): ?>
            <p style="color: green; text-align: center;">
                ✅ Deadline deleted successfully!
                <a href="?url=deadline/delete&undo=<?php echo htmlspecialchars($_GET['deleted_id'] ?? ''); ?>">Undo</a>
            </p>
        <?php elseif (isset($error)): ?>
            <p style="color: red; text-align: center;">❌ <?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (!empty($deadlines)): ?>
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
                <?php foreach ($deadlines as $deadline): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($deadline['deadline_id']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['task_id']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['description']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['due_date']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['status']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['priority']); ?></td>
                        <td>
                            <a class="delete-link" href="?url=deadline/delete/<?php echo htmlspecialchars($deadline['deadline_id']); ?>" onclick="return confirm('Are you sure you want to delete this deadline?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No deadlines found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
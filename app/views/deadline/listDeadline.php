<?php
$title = "Deadline List";

// Database connection
$conn = new mysqli("localhost", "root", "", "todolist_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch deadlines
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
        h1 {
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
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
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
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <a href="?url=deadline/index">← Back to Home</a>
        
        <table>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Task ID</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Priority</th>
            </tr>
            <?php if (!empty($deadlines)): ?>
                <?php foreach ($deadlines as $deadline): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($deadline['deadline_id']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['task_id']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['description']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['due_date']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['status']); ?></td>
                        <td><?php echo htmlspecialchars($deadline['priority']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No deadlines found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
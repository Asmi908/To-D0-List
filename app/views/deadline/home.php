<?php
$title = "Deadlines";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
        }

        .container-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 { 
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .button-link {
            display: inline-block;
            padding: 12px 24px;
            text-decoration: none;
            color: #fff;
            border-radius: 4px;
            min-width: 150px;
            text-align: center;
        }

        .add-link { background: #4CAF50; }
        .add-link:hover { background: #45a049; }
        .edit-link { background: #2196F3; }
        .edit-link:hover { background: #1e88e5; }
        .delete-link { background: #ff4444; }
        .delete-link:hover { background: #cc0000; }
        .list-link { background: #ff9800; }
        .list-link:hover { background: #f57c00; }
    </style>
</head>
<body>
   
    
    <div class="container-wrapper">
        <div class="container">
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <div class="button-container">
                <a href="?url=deadline/add" class="button-link add-link">Add Deadline</a>
                <a href="?url=deadline/edit" class="button-link edit-link">Edit Deadline</a>
                <a href="?url=deadline/delete" class="button-link delete-link">Delete Deadline</a>
                <a href="?url=deadline/list" class="button-link list-link">List Deadline</a>
            </div>
        </div>
    </div>
</body>
</html>

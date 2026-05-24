<?php
// Define BASE_URL if not already defined
if (!defined('BASE_URL')) {
    define(
        'BASE_URL',
        rtrim('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/') . '/'
    );
}
 
// Display any flash messages (success or error)
if (!empty($_SESSION['flash'])): ?>
    <div class="container mt-3">
        <?php if (isset($_SESSION['flash']['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['flash']['success'], ENT_QUOTES) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
 
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['flash']['error'], ENT_QUOTES) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
 
        <?php unset($_SESSION['flash']); ?>
    </div>
<?php endif; ?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ToDo App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <!-- Bootstrap CSS (needed for flash close button) -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }
 
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            padding: 12px 24px;
            color: #ecf0f1;
        }
 
        .navbar a {
            color: #ecf0f1;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
        }
 
        .navbar a:hover {
            text-decoration: underline;
        }
 
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
        }
 
        .navbar .nav-links {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
 
    <header class="navbar">
        <div class="logo">
            <a href="<?= BASE_URL ?>">ToDoApp</a>
        </div>
        <nav class="nav-links">
            <a href="<?= BASE_URL ?>?url=category">Categories</a>
            <a href="<?= BASE_URL ?>?url=task">Tasks</a>
            <a href="<?= BASE_URL ?>?url=note">Notes</a>
            <a href="<?= BASE_URL ?>?url=deadline">Deadlines</a>
            <a href="<?= BASE_URL ?>?url=auth/logout">Logout</a>
        </nav>
    </header>
 
    <!-- Bootstrap JS (for flash dismiss) -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    ></script>
 
 
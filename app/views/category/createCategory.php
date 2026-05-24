<?php
// Load config to access BASE_URL
require_once(__DIR__ . '/../../config/config.php');
// Extract data passed from the controller
extract($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/categoryStyle.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Add New Category</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="<?= BASE_URL ?>?url=category&action=create">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <select class="form-select" id="category_name_select" onchange="document.getElementById('category_name_input').value = this.value === 'custom' ? '' : this.value;">
                            <option value="">Select a category...</option>
                            <option value="Work">Work</option>
                            <option value="Personal">Personal</option>
                            <option value="Home">Home</option>
                            <option value="Shopping">Shopping</option>
                            <option value="Health">Health</option>
                            <option value="Others">Others</option>
                            <option value="custom">Custom...</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="category_name_input" name="category_name" placeholder="Enter custom category name..." value="" style="display: none;">
                    </div>
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Description</label>
                        <textarea class="form-control" id="category_description" name="category_description" placeholder="Describe this category..." rows="3"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Save Category</button>
                        <a href="<?= BASE_URL ?>?url=category" class="btn btn-outline-secondary ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const select = document.getElementById('category_name_select');
        const input = document.getElementById('category_name_input');

        select.addEventListener('change', function () {
            if (this.value === 'custom') {
                input.style.display = 'block';
                input.focus();
            } else {
                input.style.display = 'none';
                input.value = this.value;
            }
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            if (select.value === 'custom' && !input.value.trim()) {
                e.preventDefault();
                alert('Please enter a custom category name.');
            } else if (select.value !== 'custom' && !select.value) {
                e.preventDefault();
                alert('Please select or enter a category name.');
            }
        });
    </script>
</body>
</html>
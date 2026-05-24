<?php
// Filter Category View
// Displays the filter form for categories, extracted from index.php for better modularity

// Extract filter data from controller
$searchTerm = $data['searchTerm'] ?? '';
$statusFilter = $data['statusFilter'] ?? 'active';
require_once __DIR__ . '/../../models/Category.php';
$categoryModel = new \App\models\Category();
$categoryNames = $categoryModel->getCategoryNames();
?>

<div class="card shadow-sm mb-5">
    <div class="card-body">
        <h5 class="card-title">Filters</h5>
        <form method="GET" action="<?= BASE_URL ?>?url=category&action=list" class="row g-3 align-items-center" id="filterForm">
            <input type="hidden" name="url" value="category">
            <input type="hidden" name="action" value="list">
            <div class="col-md-4">
                <label for="search" class="form-label">Search by Name</label>
                <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" class="form-control" placeholder="Enter category name..." id="search" list="categorySuggestions" onkeypress="if(event.key === 'Enter') document.getElementById('filterForm').submit();">
                <datalist id="categorySuggestions">
                    <?php foreach ($categoryNames as $name): ?>
                        <option value="<?php echo htmlspecialchars($name); ?>">
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="active" <?php echo $statusFilter === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $statusFilter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <a href="?url=category" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>
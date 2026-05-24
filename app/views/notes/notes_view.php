<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
 
<?php
// ─── DEBUG #1: What is BASE_URL, and what is the current request URI? ───
echo "<!-- DEBUG: BASE_URL = " . BASE_URL . " -->\n";
echo "<!-- DEBUG: \$_SERVER['REQUEST_URI'] = " . $_SERVER['REQUEST_URI'] . " -->\n";
?>
 
<?php
// ─── Prepare the search‐form action string ───
$formAction = BASE_URL . '?url=note&action=list' . (!empty($taskId) ? '&task_id=' . intval($taskId) : '');
// ─── DEBUG #2: Show what $formAction is, and show the raw FORM start tag ───
echo "<!-- DEBUG: \$formAction = $formAction -->\n";
echo "<!-- DEBUG: raw <form> start tag -> ";
echo '<form method="get" action="' . htmlspecialchars($formAction, ENT_QUOTES) . '" class="search-form">';
echo " -->\n";
?>
 
<?php
// ─── Highlight function (optional) ───
$highlightFunc = function(string $text, string $term): string {
    if ($term === '') {
        return htmlspecialchars($text, ENT_QUOTES);
    }
    $escaped = preg_quote($term, '/');
    return preg_replace(
        "/($escaped)/i",
        '<mark class="highlight">$1</mark>',
        htmlspecialchars($text, ENT_QUOTES)
    );
};
?>
 
<!-- Include shared header/navigation (unchanged) -->
<?php include __DIR__ . '/../includes/header.php'; ?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title, ENT_QUOTES) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <!-- Bootstrap Icons -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
  >
 
  <style>
    body { background: #f5f7fa; font-family: 'Segoe UI', sans-serif; padding: 20px 0; }
    .notes-container { max-width: 900px; margin: 0 auto; padding: 0 15px; }
    .notes-header { background: #fff; border-radius: 8px; box-shadow: 0 1px 8px rgba(0,0,0,0.05); padding: 24px; margin-bottom: 24px; }
    .notes-header h1 { font-size: 1.75rem; margin-bottom: 12px; }
    .header-actions { display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; justify-content: md-start; align-items: center; margin-top: 12px; }
    .search-form .form-control { max-width: 300px; display: inline-block; }
    .recycle-btn { white-space: nowrap; }
    .no-notes-alert { margin-top: 20px; }
    .note-card { margin-bottom: 20px; }
    .note-card .card-body { padding: 16px; }
    .note-meta { font-size: 0.9rem; color: #6c757d; }
    .note-actions .btn { margin-right: 8px; }
    @media (max-width: 576px) {
      .search-form .form-control { width: 100%; margin-bottom: 10px; }
    }
    .back-tasks { margin-top: 20px; }
    mark.highlight { background-color: #fff3cd; padding: 0 2px; }
  </style>
</head>
 
<body>
  <div class="notes-container">
 
    <!-- Flash Messages (unchanged) -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
 
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
 
    <!-- Header + Search + Recycle Bin + New Note Button -->
    <div class="notes-header text-center text-md-start">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
        <h1 class="mb-3 mb-md-0"><?= htmlspecialchars($title, ENT_QUOTES) ?></h1>
 
        <div class="header-actions">
          <!-- “New Note” (when $taskId is set) -->
          <?php if (!empty($taskId)): ?>
            <a
              href="<?= BASE_URL ?>?url=note&action=create&task_id=<?= intval($taskId) ?>"
              class="btn btn-success"
            >
              <i class="bi bi-plus-lg"></i> New Note
            </a>
          <?php endif; ?>
 
          <!-- ─── Search Form ─── -->
          <!-- Replace the search form section -->
<form
    method="get"
    action="<?= htmlspecialchars($formAction, ENT_QUOTES) ?>"
    class="search-form d-flex align-items-center"
>
    <input type="hidden" name="url" value="note">
    <input type="hidden" name="action" value="list">
    <?php if (!empty($taskId)): ?>
        <input type="hidden" name="task_id" value="<?= intval($taskId) ?>">
    <?php endif; ?>
    <input
        type="text"
        name="search"
        class="form-control me-2"
        placeholder="Search notes…"
        value="<?= htmlspecialchars($searchTerm ?? '', ENT_QUOTES) ?>"
    >
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-search"></i>
    </button>
</form>
 
          <!-- Recycle Bin button -->
          <a
            href="<?= BASE_URL ?>?url=note&action=recycle_bin"
            class="btn btn-outline-secondary recycle-btn"
          >
            <i class="bi bi-trash-fill"></i> Recycle Bin
          </a>
        </div>
      </div>
 
      <?php if (!empty($searchTerm)): ?>
        <p class="mt-2 text-muted">
          Showing results for “<strong><?= htmlspecialchars($searchTerm, ENT_QUOTES) ?></strong>”
        </p>
      <?php endif; ?>
    </div>
 
    <!-- Note Count & List (unchanged) -->
    <?php if (!empty($notes)): ?>
      <div class="mb-3">
        <span class="badge bg-secondary">
          <?= count($notes) ?> note<?= (count($notes) === 1 ? '' : 's') ?> found
        </span>
      </div>
    <?php endif; ?>
 
    <?php if (empty($notes)): ?>
      <div class="alert alert-secondary text-center no-notes-alert" role="alert">
        No notes found.
      </div>
    <?php else: ?>
      <?php foreach ($notes as $noteItem): ?>
        <div class="card note-card shadow-sm">
          <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
              <div class="flex-grow-1">
                <h5 class="card-title mb-2">
                  <a
                    href="<?= BASE_URL ?>?url=note&action=view&id=<?= intval($noteItem['note_id']) ?>"
                    class="text-decoration-none text-dark"
                  >
                    <?php
                      $rawExcerpt = substr($noteItem['content'], 0, 80);
                      $hasMore    = (strlen($noteItem['content']) > 80);
                      echo nl2br(
                        $highlightFunc(
                          $rawExcerpt . ($hasMore ? '…' : ''),
                          $searchTerm ?? ''
                        )
                      );
                    ?>
                  </a>
                </h5>
                <p class="mb-1">
                  <small class="text-muted">
                    Task:
                    <?php if (!empty($noteItem['task_title'])): ?>
                      <a
                        href="<?= BASE_URL ?>?url=task/viewFullTask/<?= intval($noteItem['task_id']) ?>"
                        class="text-decoration-none"
                      >
                        <?= htmlspecialchars($noteItem['task_title'], ENT_QUOTES) ?>
                      </a>
                    <?php else: ?>
                      Task #<?= intval($noteItem['task_id']) ?>
                    <?php endif; ?>
                  </small>
                </p>
                <p class="note-meta mb-0">
                  <i class="bi bi-calendar2-event"></i>
                  <?= date('Y-m-d H:i', strtotime($noteItem['ncreated_date'])) ?>
                </p>
              </div>
 
              <div class="mt-3 mt-md-0 note-actions">
                <a
                  href="<?= BASE_URL ?>?url=note&action=view&id=<?= intval($noteItem['note_id']) ?>"
                  class="btn btn-sm btn-outline-primary me-1"
                  title="View note"
                >
                  <i class="bi bi-eye-fill"></i>
                </a>
                <a
                  href="<?= BASE_URL ?>?url=note&action=update&id=<?= intval($noteItem['note_id']) ?>"
                  class="btn btn-sm btn-outline-secondary me-1"
                  title="Edit note"
                >
                  <i class="bi bi-pencil-fill"></i>
                </a>
                <a
                  href="<?= BASE_URL ?>?url=note&action=delete&id=<?= intval($noteItem['note_id']) ?>"
                  class="btn btn-sm btn-outline-danger"
                  onclick="return confirm('Delete this note?');"
                  title="Delete note"
                >
                  <i class="bi bi-trash-fill"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
 
    <!-- Back to Tasks Link -->
    <div class="text-center back-tasks">
      <a href="<?= BASE_URL ?>?url=task" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Tasks
      </a>
    </div>
  </div>
 
  <!-- Bootstrap JS (including Popper) -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
</body>
</html>
 
 
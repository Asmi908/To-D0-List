<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
 
<!-- Include your shared header (adjust path if needed) -->
<?php include __DIR__ . '/../includes/header.php'; ?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Note #<?= intval($note['note_id']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <style>
    body {
      background: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
      padding: 20px 0;
    }
    .detail-container {
      max-width: 700px;
      margin: 0 auto;
      padding: 0 15px;
    }
    .note-header {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 8px rgba(0,0,0,0.05);
      padding: 24px;
      margin-bottom: 24px;
      text-align: center;
    }
    .note-header h1 {
      font-size: 1.75rem;
      margin-bottom: 8px;
    }
    .note-meta span {
      display: inline-block;
      margin: 0 8px;
      color: #6c757d;
      font-size: 0.9rem;
    }
 
    .note-content-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 8px rgba(0,0,0,0.05);
      padding: 24px;
      margin-bottom: 24px;
    }
    .note-content-card p {
      white-space: pre-wrap;
      color: #343a40;
      font-size: 1rem;
      line-height: 1.5;
    }
 
    .action-buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 16px;
      margin-bottom: 24px;
    }
    .action-buttons a {
      min-width: 120px;
    }
 
    .back-link {
      display: inline-block;
      color: #6c757d;
      text-decoration: none;
      font-size: 0.9rem;
      margin-top: 16px;
    }
    .back-link:hover {
      text-decoration: underline;
      color: #343a40;
    }
  </style>
</head>
<body>
 
  <div class="detail-container">
 
    <!-- Header: Note ID & Parent Task -->
    <div class="note-header">
      <h1>Note #<?= intval($note['note_id']) ?></h1>
      <?php if (!empty($note['task_title'])): ?>
        <p class="mb-0">
          <small class="text-muted">
            In Task:
            <a
              href="<?= BASE_URL ?>?url=task/viewFullTask/<?= intval($note['task_id']) ?>"
              class="text-decoration-none"
            >
              <?= htmlspecialchars($note['task_title']) ?>
            </a>
          </small>
        </p>
      <?php endif; ?>
      <div class="note-meta mt-2">
        <span><strong>Created on:</strong> <?= date('Y-m-d H:i', strtotime($note['ncreated_date'])) ?></span>
        <?php if ($note['is_deleted']): ?>
          <span class="text-danger"><strong>Status:</strong> Deleted</span>
        <?php endif; ?>
      </div>
    </div>
 
    <!-- Note Content -->
    <div class="note-content-card">
      <p><?= htmlspecialchars($note['content']) ?></p>
    </div>
 
    <!-- Action Buttons: Edit / Delete or Restore, plus navigation -->
    <div class="action-buttons">
      <a
        href="<?= BASE_URL ?>?url=note&action=update&id=<?= intval($note['note_id']) ?>"
        class="btn btn-primary"
      >
        Edit
      </a>
 
      <?php if (!$note['is_deleted']): ?>
        <a
          href="<?= BASE_URL ?>?url=note&action=delete&id=<?= intval($note['note_id']) ?>"
          class="btn btn-danger"
          onclick="return confirm('Delete this note?');"
        >
          Delete
        </a>
      <?php else: ?>
        <a
          href="<?= BASE_URL ?>?url=note&action=restore&id=<?= intval($note['note_id']) ?>"
          class="btn btn-success"
        >
          Restore
        </a>
      <?php endif; ?>
 
      <a
        href="<?= BASE_URL ?>?url=note"
        class="btn btn-outline-secondary"
      >
        Back to All Notes
      </a>
      <a
        href="<?= BASE_URL ?>?url=task"
        class="btn btn-outline-dark"
      >
        Back to Tasks
      </a>
    </div>
 
  </div>
 
  <!-- Bootstrap JS -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
</body>
</html>
 
 
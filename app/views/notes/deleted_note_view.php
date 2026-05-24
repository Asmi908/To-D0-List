<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Recycle Bin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <style>
    body {
      background: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
      padding: 20px 0;
    }
    .container {
      max-width: 900px;
      margin: 0 auto;
      padding: 0 15px;
    }
    .recycle-header {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 8px rgba(0, 0, 0, 0.05);
      padding: 24px;
      margin-bottom: 24px;
      text-align: center;
    }
    .recycle-header h1 {
      font-size: 1.75rem;
      margin-bottom: 8px;
    }
    .empty-alert {
      margin-top: 20px;
    }
    .table-responsive {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 8px rgba(0, 0, 0, 0.05);
      padding: 16px;
    }
    .table th,
    .table td {
      vertical-align: middle;
    }
    .note-excerpt {
      white-space: pre-wrap;
      font-size: 0.95rem;
      color: #343a40;
    }
    .table thead th {
      background-color: #e9ecef;
      font-weight: 600;
    }
    .actions a {
      margin-right: 12px;
      font-size: 0.9rem;
    }
    .back-link {
      display: inline-block;
      margin-top: 24px;
      color: #6c757d;
      text-decoration: none;
      font-size: 0.9rem;
    }
    .back-link:hover {
      text-decoration: underline;
      color: #343a40;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="recycle-header">
      <h1>Recycle Bin</h1>
    </div>
 
    <?php if (empty($deletedNotes)): ?>
      <div class="alert alert-secondary text-center empty-alert" role="alert">
        Your recycle bin is empty.
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th scope="col">Content Excerpt</th>
              <th scope="col">Task</th>
              <th scope="col">Deleted On</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($deletedNotes as $note): ?>
              <tr>
                <td class="note-excerpt">
                  <?= nl2br(htmlspecialchars(strlen($note['content']) > 100
                        ? substr($note['content'], 0, 100) . '…'
                        : $note['content'], ENT_QUOTES)) ?>
                </td>
                <td>
                  <?php if (!empty($note['task_title'])): ?>
                    <a
                      href="<?= BASE_URL ?>?url=task/viewFullTask/<?= intval($note['task_id']) ?>"
                      class="text-decoration-none"
                    >
                      <?= htmlspecialchars($note['task_title'], ENT_QUOTES) ?>
                    </a>
                  <?php else: ?>
                    <em>Task Deleted</em>
                  <?php endif; ?>
                </td>
                <td>
                  <small class="text-muted">
                    <?= date('Y-m-d H:i', strtotime($note['ncreated_date'])) ?>
                  </small>
                </td>
                <td class="actions">
                  <a
                    href="<?= BASE_URL ?>?url=note&action=restore&id=<?= intval($note['note_id']) ?>"
                    class="btn btn-sm btn-success"
                  >
                    Restore
                  </a>
                  <?php /* Uncomment if you implement a permanent delete:
                  <a
                    href="<?= BASE_URL ?>?url=note&action=purge&id=<?= intval($note['note_id']) ?>"
                    class="btn btn-sm btn-danger"
                    onclick="return confirm('Permanently delete this note?');"
                  >
                    Delete Permanently
                  </a>
                  */ ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
 
    <!-- Back to All Notes -->
    <div class="text-center">
      <a href="<?= BASE_URL ?>?url=note" class="btn btn-outline-secondary back-link">
        &larr; Back to All Notes
      </a>
    </div>
  </div>
 
  <!-- Bootstrap JS -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
</body>
</html>
 
 
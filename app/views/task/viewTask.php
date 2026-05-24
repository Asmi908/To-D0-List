<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
<?php include __DIR__ . '/../includes/header.php'; ?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Task #<?= intval($task['task_id']) ?> — <?= htmlspecialchars($task['task_title'], ENT_QUOTES) ?></title>
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
      padding-top: 20px;
    }
    .container {
      max-width: 900px;
      margin: 0 auto;
      padding: 0 15px;
    }
    /* Task Header */
    .task-details {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 8px rgba(0,0,0,0.05);
      padding: 24px;
      margin-bottom: 24px;
    }
    .task-title {
      font-size: 1.75rem;
      margin-bottom: 0;
    }
    .important-badge {
      display: inline-block;
      background-color: #e74c3c;
      color: #fff;
      padding: 4px 10px;
      font-size: 0.85rem;
      border-radius: 4px;
      margin-left: 10px;
    }
    .task-meta span {
      margin-right: 20px;
      font-size: 0.95rem;
      color: #555;
    }
 
    /* Deadline Card */
    .deadline-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 1px 8px rgba(0,0,0,0.05);
      padding: 24px;
      margin-bottom: 24px;
    }
    .deadline-card h3 {
      margin-bottom: 12px;
      font-size: 1.25rem;
    }
    .deadline-card .meta div {
      margin-bottom: 6px;
      font-size: 0.95rem;
      color: #555;
    }
 
    /* Notes List */
    .notes-list {
      margin-bottom: 24px;
    }
    .notes-list h2 {
      font-size: 1.5rem;
      margin-bottom: 16px;
    }
 
    /* Add Note Button */
    .show-note-form-btn {
      margin-bottom: 20px;
    }
 
    /* Back Link */
    .back-link {
      display: inline-block;
      margin-top: 20px;
      font-size: 0.9rem;
      color: #6c757d;
      text-decoration: none;
    }
    .back-link:hover {
      color: #343a40;
      text-decoration: underline;
    }
  </style>
</head>
<body>
 
  <div class="container">
 
    <!-- (1) Task Header -->
    <div class="task-details">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
        <h1 class="task-title">
          Task #<?= intval($task['task_id']) ?> — <?= htmlspecialchars($task['task_title'], ENT_QUOTES) ?>
          <?php if (!empty($task['task_is_important'])): ?>
            <span class="important-badge">Important</span>
          <?php endif; ?>
        </h1>
      </div>
      <div class="task-meta mt-3">
        <span><strong>Status:</strong> <?= htmlspecialchars($task['task_status'], ENT_QUOTES) ?></span>
        <span><strong>Category:</strong> <?= htmlspecialchars($task['category_name'], ENT_QUOTES) ?></span>
      </div>
    </div>
 
    <!-- (2) Deadline Section (if present) -->
    <?php if (!empty($deadline)): ?>
      <div class="deadline-card">
        <h3>Deadline Details</h3>
        <div class="meta">
          <div><strong>Due Date:</strong> <?= htmlspecialchars($deadline['due_date'], ENT_QUOTES) ?></div>
          <div><strong>Status:</strong> <?= htmlspecialchars($deadline['status'], ENT_QUOTES) ?></div>
          <div><strong>Priority:</strong> <?= htmlspecialchars($deadline['priority'], ENT_QUOTES) ?></div>
        </div>
        <?php if (!empty($deadline['description'])): ?>
          <hr>
          <p><?= nl2br(htmlspecialchars($deadline['description'], ENT_QUOTES)) ?></p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
 
    <!-- (3) Existing Notes for This Task -->
    <div class="notes-list">
      <h2>Notes for This Task</h2>
 
      <?php if (empty($notes)): ?>
        <div class="alert alert-secondary" role="alert">
          No notes yet.
        </div>
      <?php else: ?>
        <?php foreach ($notes as $noteItem): ?>
          <?php
            $note = $noteItem;
            include __DIR__ . '/../notes/note_item.php';
          ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
 
    <!-- (4) “Add New Note” Button -->
    <button
      id="showNoteFormBtn"
      class="btn btn-primary show-note-form-btn"
    >
      + Add New Note
    </button>
 
    <!-- (5) Embedded “Add a New Note” Form (hidden initially) -->
    <?php
      $taskId = $task['task_id'];
      include __DIR__ . '/../notes/note_form.php';
    ?>
 
    <!-- (6) Back to Task List -->
    <a href="<?= BASE_URL ?>?url=task" class="back-link">← Back to Task List</a>
  </div>
 
  <!-- Bootstrap JS (for form toggle) -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const showBtn   = document.getElementById('showNoteFormBtn');
      const formDiv   = document.getElementById('newNoteForm');
      const cancelBtn = document.getElementById('cancelNoteBtn');
 
      showBtn.addEventListener('click', function() {
        formDiv.style.display = 'block';
        showBtn.style.display = 'none';
      });
 
      cancelBtn.addEventListener('click', function() {
        formDiv.style.display = 'none';
        showBtn.style.display = 'inline-block';
      });
    });
  </script>
</body>
</html>
 

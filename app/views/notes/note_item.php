<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
 
<div class="card mb-3 note-card shadow-sm">
  <div class="card-body">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
      <!-- Left side: excerpt, parent task, created date -->
      <div class="flex-grow-1">
        <h5 class="card-title">
          <a
            href="<?= BASE_URL ?>?url=note&action=view&id=<?= intval($note['note_id']) ?>"
            class="text-decoration-none"
          >
            <?= nl2br(htmlspecialchars(substr($note['content'], 0, 80))) ?><?= (strlen($note['content']) > 80) ? '…' : '' ?>
          </a>
        </h5>
        <p class="mb-1">
          <small class="text-muted">
            Task:
            <?php if (!empty($note['task_title'])): ?>
              <a
                href="<?= BASE_URL ?>?url=task/viewFullTask/<?= intval($note['task_id']) ?>"
                class="text-decoration-none"
              >
                <?= htmlspecialchars($note['task_title']) ?>
              </a>
            <?php else: ?>
              Task #<?= intval($note['task_id']) ?>
            <?php endif; ?>
          </small>
        </p>
        <p class="note-meta mb-0">
          <small class="text-muted">
            Created: <?= date('Y-m-d H:i', strtotime($note['ncreated_date'])) ?>
          </small>
        </p>
      </div>
 
      <!-- Right side: View / Edit / Delete links -->
      <div class="mt-3 mt-md-0 note-actions">
        <a
          href="<?= BASE_URL ?>?url=note&action=view&id=<?= intval($note['note_id']) ?>"
          class="me-3"
        >
          View
        </a>
        <a
          href="<?= BASE_URL ?>?url=note&action=update&id=<?= intval($note['note_id']) ?>"
          class="me-3"
        >
          Edit
        </a>
        <a
          href="<?= BASE_URL ?>?url=note&action=delete&id=<?= intval($note['note_id']) ?>"
          class="text-danger"
          onclick="return confirm('Delete this note?');"
        >
          Delete
        </a>
      </div>
    </div>
  </div>
</div>
 
 
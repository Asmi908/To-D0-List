<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
 
<div class="card mb-4 new-note-form" id="newNoteForm" style="display: none;">
  <div class="card-body">
    <h5 class="card-title">Add a New Note</h5>
 
    <form method="POST" action="<?= BASE_URL ?>?url=note&action=create">
    <form method="GET" action="?url=note">


      <!-- Hidden field for task ID -->
      <input
        type="hidden"
        name="task_id"
        value="<?= isset($taskId) ? intval($taskId) : '' ?>"
      >
 
      <div class="mb-3">
        <label for="content" class="form-label">Note Content</label>
        <textarea
          name="content"
          id="content"
          class="form-control"
          rows="5"
          required
          placeholder="Write your note here…"
        ><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' ?></textarea>
      </div>
 
      <?php
        if (
          isset($error) &&
          $_SERVER['REQUEST_METHOD'] === 'POST' &&
          (isset($_GET['action']) && $_GET['action'] === 'create')
        ):
      ?>
        <div class="alert alert-danger">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
 
      <div class="d-flex align-items-center">
        <button type="submit" class="btn btn-success">Create Note</button>
        <button type="button" id="cancelNoteBtn" class="btn btn-outline-secondary btn-sm ms-3">
          Cancel
        </button>
      </div>
    </form>
  </div>
</div>
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>
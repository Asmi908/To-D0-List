<?php if (!defined('BASE_URL')) die('Direct access not allowed'); ?>
 
<!-- include your shared header if needed -->
<?php include __DIR__ . '/../includes/header.php'; ?>
 
<div class="notes-container">
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Edit Note</h5>
 
      <!--
        We set the form’s action to the slash‐style route:
        /?url=note/update/{note_id}
      -->
      <form
  method="POST"
  action="<?= BASE_URL ?>?url=note&action=update&id=<?= intval($note['note_id']) ?>"
>
 
 
        <div class="mb-3">
          <label for="content" class="form-label">Note Content</label>
          <textarea
            name="content"
            id="content"
            class="form-control"
            rows="5"
            required
          ><?= htmlspecialchars($note['content'], ENT_QUOTES) ?></textarea>
        </div>
 
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger">
            <?= htmlspecialchars($error, ENT_QUOTES) ?>
          </div>
        <?php endif; ?>
 
        <div class="d-flex align-items-center">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <a
            href="<?= BASE_URL ?>?url=note/view/<?= intval($note['note_id']) ?>"
            class="btn btn-outline-secondary btn-sm ms-3"
          >
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
 
<!-- include your shared footer if needed -->
<?php include __DIR__ . '/../includes/footer.php'; ?>
 
 
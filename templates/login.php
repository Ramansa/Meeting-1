<?php include __DIR__ . '/layout_top.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-header">Sign In</div>
      <div class="card-body">
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" action="/login">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input class="form-control" name="user_login" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" required>
          </div>
          <button class="btn btn-primary w-100"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/layout_bottom.php'; ?>

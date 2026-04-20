<?php include __DIR__ . '/layout_top.php'; ?>
<div class="row justify-content-center">
  <div class="col-lg-5 col-md-7">
    <div class="text-center mb-4">
      <h2 class="fw-bold mb-1">Welcome back</h2>
      <p class="text-muted mb-0">Sign in to manage sessions and attendance.</p>
    </div>
    <div class="card shadow-sm border-0">
      <div class="card-header"><i class="fa-solid fa-lock me-2"></i>Sign In</div>
      <div class="card-body">
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" action="/login">
          <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
              <input class="form-control" name="user_login" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
              <input class="form-control" type="password" name="password" required>
            </div>
          </div>
          <button class="btn btn-primary w-100"><i class="fa-solid fa-right-to-bracket me-1"></i>Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/layout_bottom.php'; ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Meeting Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --app-primary: #4f46e5;
      --app-primary-dark: #3730a3;
      --app-surface: #ffffff;
      --app-bg: #f3f5ff;
    }
    body {
      font-family: 'Inter', sans-serif;
      background:
        radial-gradient(circle at 95% 2%, rgba(79, 70, 229, 0.18) 0, rgba(79, 70, 229, 0) 32%),
        radial-gradient(circle at 5% 0, rgba(14, 165, 233, 0.2) 0, rgba(14, 165, 233, 0) 26%),
        var(--app-bg);
      min-height: 100vh;
    }
    .navbar {
      background: linear-gradient(135deg, var(--app-primary), var(--app-primary-dark)) !important;
    }
    .app-shell {
      border: 1px solid rgba(79, 70, 229, 0.12);
      border-radius: 1rem;
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 1rem 2rem rgba(27, 31, 59, 0.08);
      backdrop-filter: blur(2px);
    }
    .card {
      border: 1px solid rgba(148, 163, 184, 0.2);
      border-radius: 0.9rem;
    }
    .card-header {
      background-color: #fff;
      border-bottom: 1px solid rgba(148, 163, 184, 0.25);
      font-weight: 600;
    }
    .btn-primary {
      background-color: var(--app-primary);
      border-color: var(--app-primary);
    }
    .btn-primary:hover,
    .btn-primary:focus {
      background-color: var(--app-primary-dark);
      border-color: var(--app-primary-dark);
    }
    .stats-card-icon {
      width: 2.25rem;
      height: 2.25rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.7rem;
      font-size: 1rem;
      margin-bottom: 0.5rem;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container-fluid">
    <span class="navbar-brand fw-semibold">
      <i class="fa-solid fa-calendar-check me-2"></i>Meeting Manager
    </span>
    <div>
      <?php if (!empty($_SESSION['auth_user'])): ?>
        <span class="text-white me-3 small">
          <i class="fa-solid fa-user me-1"></i>
          <?= htmlspecialchars($_SESSION['auth_user']['display_name']) ?>
          <span class="badge text-bg-light ms-1"><?= htmlspecialchars($_SESSION['auth_user']['role']) ?></span>
        </span>
        <a class="btn btn-sm btn-light" href="/logout"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container py-4">
  <div class="app-shell p-4 p-md-5">

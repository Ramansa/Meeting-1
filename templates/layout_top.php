<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Meeting Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <span class="navbar-brand"><i class="fa-solid fa-calendar-check"></i> Meeting Manager</span>
    <div>
      <?php if (!empty($_SESSION['auth_user'])): ?>
        <span class="text-white me-3"><?= htmlspecialchars($_SESSION['auth_user']['display_name']) ?> (<?= htmlspecialchars($_SESSION['auth_user']['role']) ?>)</span>
        <a class="btn btn-sm btn-light" href="/logout">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container py-4">

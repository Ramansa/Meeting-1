<?php include __DIR__ . '/layout_top.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-days me-2 text-primary"></i>Calendar</h4>
    <small class="text-muted">Track meetings, status, and tutor performance at a glance.</small>
  </div>
  <?php if (in_array($user['role'], ['admin', 'teacher'], true)): ?>
    <a href="/meetings/create" class="btn btn-success"><i class="fa-solid fa-plus me-1"></i>New Meeting</a>
  <?php endif; ?>
</div>

<div class="row mb-3">
  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card h-100">
      <div class="card-body">
        <span class="stats-card-icon bg-primary-subtle text-primary"><i class="fa-solid fa-list-check"></i></span>
        <small class="text-muted d-block">Total Meetings</small>
        <h4 class="mb-0 fw-bold"><?= (int)($report['totals']['total'] ?? 0) ?></h4>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card h-100">
      <div class="card-body">
        <span class="stats-card-icon bg-success-subtle text-success"><i class="fa-solid fa-circle-check"></i></span>
        <small class="text-muted d-block">Present Meetings</small>
        <h4 class="mb-0 fw-bold"><?= (int)($report['totals']['present_count'] ?? 0) ?></h4>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <span class="stats-card-icon bg-warning-subtle text-warning"><i class="fa-solid fa-chalkboard-user"></i></span>
        <small class="text-muted d-block">Top Tutor ID</small>
        <h4 class="mb-0 fw-bold"><?= htmlspecialchars((string)($report['tutor'][0]['tutor_id'] ?? '-')) ?></h4>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body p-2 p-md-3">
    <div id="calendar"></div>
  </div>
</div>

<?php if ($user['role'] === 'admin'): ?>
<div class="card shadow-sm">
  <div class="card-header"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Update Meeting Status</div>
  <div class="card-body">
    <form class="row g-3" method="post" action="/meetings/status">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Security\CsrfService::token()) ?>">
      <div class="col-md-3">
        <label class="form-label fw-semibold">Meeting ID</label>
        <input class="form-control" name="meeting_id" placeholder="Meeting ID" required>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Status</label>
        <select class="form-select" name="status">
          <option>Present</option>
          <option>Tutor Absent</option>
          <option>Student Absent</option>
          <option>Not Verified</option>
          <option>Cancelled</option>
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-end"><button class="btn btn-primary w-100">Save Status</button></div>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
const events = <?= json_encode(array_map(static function ($m) {
    return [
        'id' => $m['id'],
        'title' => $m['title'] . ' (' . $m['provider'] . ')',
        'start' => $m['start_at'],
        'end' => $m['end_at'],
        'url' => $_SESSION['auth_user']['role'] === 'teacher' ? $m['start_url'] : $m['join_url']
    ];
}, $meetings), JSON_UNESCAPED_SLASHES) ?>;

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events,
    eventClick: function(info) {
      info.jsEvent.preventDefault();
      if (info.event.url) {
        window.location.href = info.event.url;
      }
    }
  });
  calendar.render();
});
</script>
<?php include __DIR__ . '/layout_bottom.php'; ?>

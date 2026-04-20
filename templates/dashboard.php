<?php include __DIR__ . '/layout_top.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Calendar</h4>
  <?php if (in_array($user['role'], ['admin', 'teacher'], true)): ?>
    <a href="/meetings/create" class="btn btn-success"><i class="fa-solid fa-plus"></i> New Meeting</a>
  <?php endif; ?>
</div>

<div class="row mb-3">
  <div class="col-md-4"><div class="card"><div class="card-body"><small>Total Meetings</small><h5><?= (int)($report['totals']['total'] ?? 0) ?></h5></div></div></div>
  <div class="col-md-4"><div class="card"><div class="card-body"><small>Present Meetings</small><h5><?= (int)($report['totals']['present_count'] ?? 0) ?></h5></div></div></div>
  <div class="col-md-4"><div class="card"><div class="card-body"><small>Top Tutor ID</small><h5><?= htmlspecialchars((string)($report['tutor'][0]['tutor_id'] ?? '-')) ?></h5></div></div></div>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body">
    <div id="calendar"></div>
  </div>
</div>

<?php if ($user['role'] === 'admin'): ?>
<div class="card shadow-sm">
  <div class="card-header">Update Meeting Status</div>
  <div class="card-body">
    <form class="row g-3" method="post" action="/meetings/status">
      <div class="col-md-3"><input class="form-control" name="meeting_id" placeholder="Meeting ID" required></div>
      <div class="col-md-4">
        <select class="form-select" name="status">
          <option>Present</option>
          <option>Tutor Absent</option>
          <option>Student Absent</option>
          <option>Not Verified</option>
          <option>Cancelled</option>
        </select>
      </div>
      <div class="col-md-3"><button class="btn btn-primary">Save Status</button></div>
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

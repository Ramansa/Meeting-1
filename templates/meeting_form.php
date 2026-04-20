<?php include __DIR__ . '/layout_top.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-1 fw-bold"><i class="fa-solid fa-calendar-plus me-2 text-primary"></i>Create Meeting</h4>
    <small class="text-muted">Set schedule details and automatically calculate end time.</small>
  </div>
  <a href="/" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i>Back</a>
</div>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post" class="card shadow-sm border-0">
  <div class="card-body row g-3">
    <div class="col-md-6">
      <label class="form-label fw-semibold">Title</label>
      <input class="form-control" name="title" required>
    </div>
    <div class="col-md-3">
      <label class="form-label fw-semibold">Provider</label>
      <select class="form-select" name="provider" required>
        <option value="zoom">Zoom</option>
        <option value="teams">Microsoft Teams</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label fw-semibold">Duration (minutes)</label>
      <input id="duration" class="form-control" type="number" name="duration_minutes" value="60" min="15" required>
    </div>
    <div class="col-md-4">
      <label class="form-label fw-semibold">Tutor</label>
      <select class="form-select" name="tutor_id" required>
        <?php foreach ($teachers as $t): ?><option value="<?= (int)$t['ID'] ?>"><?= htmlspecialchars($t['display_name']) ?></option><?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label fw-semibold">Student</label>
      <select class="form-select" name="student_id" required>
        <?php foreach ($students as $s): ?><option value="<?= (int)$s['ID'] ?>"><?= htmlspecialchars($s['display_name']) ?></option><?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label fw-semibold">School Group ID</label>
      <input class="form-control" type="number" name="school_group_id" required>
    </div>
    <div class="col-md-2">
      <label class="form-label fw-semibold">Class Group ID</label>
      <input class="form-control" type="number" name="class_group_id" required>
    </div>
    <div class="col-md-3">
      <label class="form-label fw-semibold">Start</label>
      <input id="start_at" class="form-control" type="datetime-local" name="start_at" required>
    </div>
    <div class="col-md-3">
      <label class="form-label fw-semibold">End (auto)</label>
      <input id="end_at" class="form-control" type="datetime-local" disabled>
    </div>
    <div class="col-md-3">
      <label class="form-label fw-semibold">Lesson ID (manual)</label>
      <input class="form-control" type="number" name="lesson_id" value="0">
    </div>
    <div class="col-12 mt-2">
      <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Create Meeting</button>
    </div>
  </div>
</form>
<script>
function updateEnd() {
  const start = document.getElementById('start_at').value;
  const duration = parseInt(document.getElementById('duration').value || '0', 10);
  if (!start || !duration) return;
  const dt = new Date(start);
  dt.setMinutes(dt.getMinutes() + duration);
  const pad = n => String(n).padStart(2, '0');
  const formatted = `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
  document.getElementById('end_at').value = formatted;
}
$(document).on('change keyup', '#start_at,#duration', updateEnd);
</script>
<?php include __DIR__ . '/layout_bottom.php'; ?>

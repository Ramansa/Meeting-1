CREATE TABLE IF NOT EXISTS app_user_roles (
  user_id BIGINT UNSIGNED PRIMARY KEY,
  role ENUM('admin','teacher','peer') NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS meeting_user_groups (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  group_id BIGINT UNSIGNED NOT NULL,
  group_type ENUM('school','class') NOT NULL,
  UNIQUE KEY uniq_user_group_type (user_id, group_id, group_type)
);

CREATE TABLE IF NOT EXISTS app_tutor_availability (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tutor_id BIGINT UNSIGNED NOT NULL,
  weekday TINYINT NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL
);

CREATE TABLE IF NOT EXISTS app_lesson_packages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  program_name VARCHAR(190) NOT NULL,
  total_lessons INT NOT NULL,
  is_unlimited TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS app_user_lesson_packages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  package_id BIGINT UNSIGNED NOT NULL,
  remaining_credits INT NOT NULL DEFAULT 0,
  is_unlimited TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS app_lessons (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  program_name VARCHAR(190) NOT NULL,
  lesson_order INT NOT NULL,
  title VARCHAR(190) NOT NULL
);

CREATE TABLE IF NOT EXISTS app_meetings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  tutor_id BIGINT UNSIGNED NOT NULL,
  student_id BIGINT UNSIGNED NOT NULL,
  school_group_id BIGINT UNSIGNED NOT NULL,
  class_group_id BIGINT UNSIGNED NOT NULL,
  provider ENUM('zoom','teams') NOT NULL,
  provider_meeting_id VARCHAR(255) NOT NULL,
  join_url TEXT NOT NULL,
  start_url TEXT NOT NULL,
  start_at DATETIME NOT NULL,
  end_at DATETIME NOT NULL,
  duration_minutes INT NOT NULL,
  lesson_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('Present','Tutor Absent','Student Absent','Not Verified','Cancelled') NOT NULL DEFAULT 'Not Verified',
  recording_url TEXT NULL,
  attendance_payload LONGTEXT NULL,
  provider_payload LONGTEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS meeting_participants (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  meeting_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  attendance ENUM('Present','Absent','Not Verified') NOT NULL DEFAULT 'Not Verified'
);

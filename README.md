# Meeting-1 (WordPress-linked standalone PHP meeting manager)

This project is a standalone PHP application that links directly to WordPress/BuddyPress tables using only the `wpdb` class (no WordPress helper functions/classes are used).

## Features implemented

- Authentication against `wb_users` (password hash verification).
- Roles: `admin`, `teacher`, `peer`.
- Group links through `wb_bp_groups` + `meeting_user_groups` bridge.
- Meeting CRUD with provider sync abstraction for Zoom / Microsoft Teams.
- Provider IDs, join/start URLs stored in DB.
- Tutor availability with conflict prevention.
- Lesson packages with limited and unlimited credits.
- Auto-deduct / restore credits based on meeting status.
- Calendar views (month/week/day) via FullCalendar + Bootstrap + jQuery + Font Awesome.
- Booking by student or admin on behalf of students.
- Webhook endpoint placeholder for provider callbacks.
- Reporting page for attendance, tutor utilization, and credits.

## Quick start

1. Copy `.env.example` to `.env` and set credentials.
2. Ensure WordPress core is installed and `wp-includes/wp-db.php` is available.
3. Import `database/schema.sql`.
4. Run with PHP built-in server:

```bash
php -S 0.0.0.0:8080 -t public
```

## Notes

- This app uses `wpdb` directly for all DB calls.
- API provider clients are structured for real HTTP integration and currently include robust placeholders for payload/response mapping.
- For production, place behind Apache/Nginx and secure secrets.

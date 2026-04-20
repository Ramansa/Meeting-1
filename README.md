# Meeting-1 (WordPress-linked standalone PHP meeting manager)

Meeting-1 is a standalone PHP application that schedules and tracks online lessons while integrating with existing WordPress/BuddyPress user/group data.

The app uses `wpdb` directly (from WordPress core) for database access and does **not** rely on higher-level WordPress helper APIs.

## What this app does

- Authenticates users against WordPress users (`{prefix}users`) using password hash verification.
- Uses app-specific roles: `admin`, `teacher`, and `peer`.
- Maps users to BuddyPress groups through a bridge table for school/class context.
- Creates meetings with provider abstraction (`zoom` or `teams`).
- Stores provider meeting metadata (`provider_meeting_id`, `join_url`, `start_url`, payload fields).
- Prevents tutor booking conflicts and validates basic scheduling data.
- Manages lesson credits (limited and unlimited packages), including status-based deduction/restore behavior.
- Displays calendar/reporting data in a Bootstrap + FullCalendar dashboard.

## Tech stack

- PHP 8+
- WordPress core `wpdb` (`wp-includes/wp-db.php`)
- MySQL/MariaDB
- Bootstrap, jQuery, FullCalendar, Font Awesome (via templates)

## Project structure

```text
public/index.php             Front controller and route dispatch
src/Controller/              HTTP controller actions
src/Auth/                    Login/session role checks
src/Repository/              Data access via wpdb
src/Service/                 Meeting/business logic + provider factory
src/Provider/                Provider clients (Zoom/Teams)
templates/                   PHP views
database/schema.sql          App-specific SQL schema
.env.example                 Environment variable template
```

## Requirements

1. PHP 8.0+ with common extensions (`pdo_mysql`, `mbstring`, etc.).
2. A WordPress installation reachable by this app.
3. Access to `wp-includes/wp-db.php` in that WordPress install.
4. MySQL/MariaDB user with rights to WordPress and app tables.

## Installation & setup

1. Clone this repository.
2. Copy environment template:

   ```bash
   cp .env.example .env
   ```

3. Update `.env` values (see [Environment variables](#environment-variables)).
4. Import app tables:

   ```bash
   mysql -u <user> -p <database> < database/schema.sql
   ```

5. Ensure WordPress core is available so `wpdb` can be loaded.
6. Start local server:

   ```bash
   php -S 0.0.0.0:8080 -t public
   ```

7. Open:

   - `http://localhost:8080/login`

## Environment variables

| Variable | Description |
|---|---|
| `APP_ENV` | Environment label (example: `local`, `production`). |
| `APP_DEBUG` | Debug mode flag (`true`/`false`). |
| `APP_URL` | Base URL for the app. |
| `APP_KEY` | App secret value (set to strong random string). |
| `WP_DB_NAME` | WordPress database name. |
| `WP_DB_USER` | WordPress database user. |
| `WP_DB_PASSWORD` | WordPress database password. |
| `WP_DB_HOST` | Database host. |
| `WP_DB_PREFIX` | Table prefix (example: `wb_`). |
| `ZOOM_CLIENT_ID` | Zoom OAuth client ID (if using Zoom). |
| `ZOOM_CLIENT_SECRET` | Zoom OAuth client secret. |
| `ZOOM_ACCOUNT_ID` | Zoom account identifier. |
| `TEAMS_TENANT_ID` | Microsoft tenant ID (if using Teams). |
| `TEAMS_CLIENT_ID` | Microsoft app client ID. |
| `TEAMS_CLIENT_SECRET` | Microsoft app client secret. |
| `DEFAULT_PROVIDER` | Default meeting provider (`zoom` or `teams`). |

## HTTP routes

| Route | Method(s) | Purpose |
|---|---|---|
| `/login` | GET, POST | Render login form and authenticate user. |
| `/logout` | POST | End user session. |
| `/` | GET | Dashboard/calendar + summary report data. |
| `/meetings/create` | GET, POST | Meeting creation form and submit flow. |
| `/meetings/status` | POST | Admin-only status update for meetings. |
| `/webhooks/provider` | POST | Provider webhook intake placeholder (logs payload). |

## Role permissions

- **admin**
  - Dashboard access.
  - Create meetings.
  - Update meeting statuses.
- **teacher**
  - Dashboard access.
  - Create meetings.
- **peer**
  - Dashboard read access (based on repository filters).
  - No meeting creation/status update endpoints.

## Database tables (app schema)

Created by `database/schema.sql`:

- `app_user_roles`
- `meeting_user_groups`
- `app_tutor_availability`
- `app_lesson_packages`
- `app_user_lesson_packages`
- `app_lessons`
- `app_meetings`
- `meeting_participants`

Also expected from WordPress/BuddyPress context (prefix from `WP_DB_PREFIX`), such as users and BP groups.

## Security behavior currently implemented

- Session cookie settings with `httponly` and `samesite=Lax`.
- HTTPS-aware `secure` cookie flag.
- CSRF token checks on state-changing form endpoints.
- Basic secure headers:
  - `X-Frame-Options: DENY`
  - `X-Content-Type-Options: nosniff`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Permissions-Policy: geolocation=(), microphone=(), camera=()`
  - HSTS enabled when running under HTTPS

## Provider/webhook notes

- `ZoomClient` and `TeamsClient` are structured for integration, with placeholder-safe behavior around payload mapping.
- `/webhooks/provider` currently appends raw payload lines to:

  ```text
  storage/logs/webhook.log
  ```

  Ensure the directory is writable in your environment.

## Troubleshooting

- **Login always fails**
  - Confirm `WP_DB_PREFIX` and user table data.
  - Verify passwords were created with WordPress-compatible hashing.
- **Database connection errors**
  - Recheck `WP_DB_HOST`, credentials, and DB privileges.
- **Meeting creation denied**
  - Confirm current user has `admin` or `teacher` in `app_user_roles`.
- **Webhook log write failure**
  - Create `storage/logs` and grant write permissions.

## Screenshot

No project screenshot is currently included in this repository. If you want, add a PNG/JPG under a docs folder (for example `docs/dashboard.png`) and reference it here.

## Production hardening checklist

- Put app behind Nginx/Apache with HTTPS enforced.
- Rotate and secure provider credentials.
- Add rate limiting and audit logging.
- Restrict webhook endpoint by signature/IP policy.
- Replace placeholder provider logic with full API error/retry handling.


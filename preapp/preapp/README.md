# Simulearn360 (prototype)

A PHP business-simulation platform inspired by gry.revas.pl — players join simulations,
submit per-round decisions, and compete on a leaderboard. Instructors create and manage
simulations (role-gated, instructor pages to be built out next).

## Setup

1. Copy the `preapp` folder to your webroot (e.g., XAMPP `htdocs`).
2. Import the schema: `mysql -u root -p < db.sql`
3. Update credentials in `includes/config.php` if needed.
4. Open `http://localhost/preapp/index.php`.
5. Demo logins (work even without a DB connection): `player@demo.com` / `demo`,
   `instructor@demo.com` / `demo`.

> Note: `db.sql` ships with placeholder password hashes for the seeded demo users.
> Replace them with the real output of `password_hash('demo', PASSWORD_DEFAULT)`
> if you want DB-backed login to work for those two accounts; otherwise the
> hardcoded demo-mode fallback in `index.php` covers it.

## Structure

- `index.php` — login (also the canonical login screen; `login.php` redirects here)
- `register.php` — sign up as player or instructor, writes to `users` table
- `dashboard.php` — player home: active sims, recent results, leaderboard snapshot
- `simulations.php` — list joined sims + catalog of sims you can join
- `join.php` — join a simulation (optionally via license key)
- `play.php` — submit per-round decisions (price, marketing, production, R&D)
- `results.php` — full round-by-round results table
- `leaderboard.php` — global rankings across all simulations
- `certificates.php` — completed-simulation certificates
- `profile.php` / `settings.php` — account details & password change
- `logout.php` — destroys session
- `restore-password.php` — forgot-password request flow (email sending is a TODO)
- `includes/config.php` — PDO connection + session/auth/CSRF helpers
- `includes/head.php`, `includes/dashboard_layout.php` — shared chrome
- `js/app.js` — password toggle, language dropdown, auth background slideshow, toasts
- `db.sql` — full schema: users, simulations, games, decisions, results, certificates

## Next steps

- Instructor-side pages: `manage-groups.php`, `manage-simulations.php`, `reports.php`
  (sidebar already links to these; not yet built)
- Wire `play.php` decisions to a real scoring engine that produces `results` rows
  and advances `games.current_round`
- Real email delivery for `restore-password.php`
- OAuth callback handlers for `auth/google.php` / `auth/microsoft.php` (buttons exist,
  endpoints don't yet)
- Replace demo-mode auth fallback once a live MySQL instance is always available

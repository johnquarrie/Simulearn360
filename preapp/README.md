# Simulearn360 (prototype)

This is a lightweight PHP prototype of a business simulation website. It includes a simple front-end and a MySQL schema.

Setup

1. Copy the `preapp` folder to your webroot (e.g., XAMPP `htdocs`).
2. Create the database and tables: import `db.sql` into your MySQL server. Example using CLI:

```bash
mysql -u root -p < db.sql
```

3. Update database credentials in `config.php` if necessary.
4. Open `http://localhost/preapp/index.php` (adjust path to your environment).

Files added

- `index.php` — homepage
- `mygames.php` — list user's games (prototype)
- `join.php` — join simulation form
- `certificates.php` — certificates listing
- `ranking.php` — rankings
- `config.php` — DB config and connection helper
- `styles.css` — simple site styling
- `db.sql` — SQL schema and sample data

Notes

- This is a starting point. The code is intentionally simple for learning and extension.
- Sanitize and secure inputs before production. Add authentication, routing, and template separation as next steps.

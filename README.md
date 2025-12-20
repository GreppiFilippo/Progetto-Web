# Mensa Campus

Simple web application for cafeteria meal booking and menu management.

## Overview
Mensa Campus provides user registration/login, daily menu browsing, reservations, and an admin panel to manage dishes.

## Requirements
- PHP (>= 7.4)
- MySQL / MariaDB
- Web server (e.g. XAMPP)

## Quick setup
1. Place project in your web root (e.g. XAMPP `htdocs`).
2. Create the database using [www/db/creazione_db.sql](www/db/creazione_db.sql) and populate with [www/db/insert_data.sql](www/db/insert_data.sql).
3. Configure database credentials in [www/bootstrap.php](www/bootstrap.php) / [www/db/database.php](www/db/database.php).
4. Start the server and open [www/index.php](www/index.php) in your browser.

## Report
Report available [here](doc/report.md).

## Run locally
- Start Apache/MySQL (e.g. XAMPP Control Panel).
- Visit http://localhost/progetto-web/www/

## License
MIT — see [LICENSE](LICENSE)

## Authors
- Filippo Greppi
- Ettore Spaccini
- Marcello Spagnoli

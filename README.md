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

## Main files
- Frontend pages: [www/index.php](www/index.php), [www/menu.php](www/menu.php), [www/login.php](www/login.php), [www/register.php](www/register.php), [www/user-profile.php](www/user-profile.php), [www/user-bookings.php](www/user-bookings.php)
- Admin pages: [www/admin-add-dish.php](www/admin-add-dish.php), admin templates under [www/template](www/template)
- Templates: [www/template/base-user.php](www/template/base-user.php), [www/template/base-admin.php](www/template/base-admin.php)
- Styles & assets: [www/css/style.css](www/css/style.css), [www/js/menu.js](www/js/menu.js), [www/upload/](www/upload/)
- Utilities: [`getNewNavItem`](www/utils/functions.php), [`isUserLoggedIn`](www/utils/functions.php) in [www/utils/functions.php](www/utils/functions.php)
- DB helper: [`DatabaseHelper`](www/db/database.php) in [www/db/database.php](www/db/database.php)

## Run locally
- Start Apache/MySQL (e.g. XAMPP Control Panel).
- Visit http://localhost/your-project-folder/www/index.php

![](use-case-diagram.svg)

## License
MIT — see [LICENSE](LICENSE)
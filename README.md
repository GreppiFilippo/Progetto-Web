# Mensa Campus

Applicazione web per la prenotazione dei pasti della mensa e la gestione del menù.

## Panoramica
Mensa Campus offre registrazione/login per gli utenti, consultazione del menù giornaliero, prenotazioni e un pannello di amministrazione per gestire i piatti.

## Requisiti
- PHP (>= 7.4)
- MySQL / MariaDB
- Server web (es. XAMPP)

## Installazione rapida
1. Posiziona il progetto nella root del web (es. `htdocs` di XAMPP).
2. Crea il database usando [www/db/creazione_db.sql](www/db/creazione_db.sql) e popolalo con [www/db/insert_data.sql](www/db/insert_data.sql).
3. Configura le credenziali del database in [www/bootstrap.php](www/bootstrap.php) / [www/db/database.php](www/db/database.php).
4. Avvia il server e apri [www/index.php](www/index.php) nel tuo browser.

## Report
Report disponibile [qui](doc/report.md).

## Esecuzione locale
- Avvia Apache e MySQL (es. XAMPP Control Panel).
- Visita http://localhost/progetto-web/www/

## Licenza
MIT — vedi [LICENSE](LICENSE)

## Autori
- Filippo Greppi
- Ettore Spaccini
- Marcello Spagnoli

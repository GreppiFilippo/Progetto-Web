-- =====================================================
-- QUERY UTILI PER DATABASE MENSA CAMPUS
-- =====================================================

USE cafeteria;

-- =====================================================
-- 1. PAGINA MENU - Visualizzazione piatti per categoria
-- =====================================================

-- Recupera tutti i piatti disponibili raggruppati per categoria
SELECT d.dish_id, d.name, d.description, d.price, d.stock, d.calories, d.image,
       c.category_name,
       CASE WHEN d.stock > 0 THEN 'Disponibile' ELSE 'Esaurito' END AS availability_status
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
ORDER BY c.category_name, d.name;

-- Filtra piatti per categoria specifica (per il menu dropdown)
SELECT d.dish_id, d.name, d.description, d.price, d.stock, d.calories, d.image
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
WHERE c.category_name = 'Primi Piatti'  -- Cambiare in: Secondi Piatti, Contorni, Dolci, Bevande
ORDER BY d.name;

-- Cerca piatti per nome (per la barra di ricerca)
SELECT d.dish_id, d.name, d.description, d.price, d.stock, d.calories, d.image, c.category_name
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
WHERE d.name LIKE '%pasta%'  -- sostituire con il termine cercato
ORDER BY c.category_name, d.name;

-- Recupera piatti disponibili (stock > 0)
SELECT d.dish_id, d.name, d.description, d.price, d.stock, d.calories, d.image, c.category_name
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
WHERE d.stock > 0
ORDER BY c.category_name, d.name;

-- =====================================================
-- 2. ADMIN - Gestione Piatti e Menu
-- =====================================================

-- Lista completa piatti per admin-menu.html
SELECT d.dish_id, d.name, d.description, d.price, d.stock, d.calories, d.image,
       c.category_name,
       CASE 
         WHEN d.stock = 0 THEN 'Esaurito'
         WHEN d.stock < 10 THEN 'Scorte basse'
         ELSE 'Disponibile'
       END AS stock_status
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
ORDER BY c.category_name, d.name;

-- Dettaglio piatto per modifica (admin-add-dish.php in modalità edit)
SELECT d.dish_id, d.name, d.description, d.price, d.stock, d.calories, d.image,
       c.category_id, c.category_name
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
WHERE d.dish_id = 1;  -- sostituire con l'ID del piatto

-- Recupera specifiche dietetiche di un piatto
SELECT ds.dietary_spec_name
FROM dish_specifications dsp
JOIN dietary_specifications ds ON dsp.dietary_spec_id = ds.dietary_spec_id
WHERE dsp.dish_id = 1;  -- sostituire con l'ID del piatto

-- Inserimento nuovo piatto (usato in admin-add-dish.php)
INSERT INTO dishes (name, description, price, stock, image, calories, category_id)
VALUES ('Pasta al Pomodoro', 'Pasta con salsa di pomodoro fresco, basilico e parmigiano', 4.50, 25, 'pasta-pomodoro.jpg', 350, 1);

-- Aggiungi specifiche dietetiche al piatto appena creato
INSERT INTO dish_specifications (dish_id, dietary_spec_id)
VALUES 
    (LAST_INSERT_ID(), 1),  -- Vegetariano
    (LAST_INSERT_ID(), 3);  -- Senza Lattosio

-- Aggiorna piatto esistente
UPDATE dishes
SET name = 'Pasta al Pomodoro',
    description = 'Pasta con salsa di pomodoro fresco, basilico e parmigiano',
    price = 4.50,
    stock = 30,
    calories = 350,
    category_id = 1
WHERE dish_id = 1;

-- Elimina e reinserisci specifiche dietetiche (per aggiornamento)
DELETE FROM dish_specifications WHERE dish_id = 1;
INSERT INTO dish_specifications (dish_id, dietary_spec_id) VALUES (1, 1), (1, 3);

-- Elimina un piatto (solo se non presente in prenotazioni)
DELETE FROM dish_specifications WHERE dish_id = 1;
DELETE FROM dishes WHERE dish_id = 1;

-- Recupera tutte le categorie disponibili (per il dropdown)
SELECT category_id, category_name
FROM categories
ORDER BY category_name;

-- Recupera tutte le specifiche dietetiche disponibili (per checkbox)
SELECT dietary_spec_id, dietary_spec_name
FROM dietary_specifications
ORDER BY dietary_spec_name;

-- =====================================================
-- 3. USER - Nuova Prenotazione (user-bookings.html)
-- =====================================================

-- Recupera piatti disponibili per la prenotazione, raggruppati per categoria
SELECT d.dish_id, d.name, d.description, d.price, d.stock, d.calories,
       c.category_name
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
WHERE d.stock > 0
ORDER BY 
    CASE c.category_name
        WHEN 'Primi Piatti' THEN 1
        WHEN 'Secondi Piatti' THEN 2
        WHEN 'Contorni' THEN 3
        WHEN 'Dolci' THEN 4
        WHEN 'Bevande' THEN 5
        ELSE 6
    END, d.name;

-- Recupera specifiche dietetiche per ogni piatto (per mostrare badge)
SELECT d.dish_id, GROUP_CONCAT(ds.dietary_spec_name SEPARATOR ', ') AS dietary_specs
FROM dishes d
LEFT JOIN dish_specifications dsp ON d.dish_id = dsp.dish_id
LEFT JOIN dietary_specifications ds ON dsp.dietary_spec_id = ds.dietary_spec_id
GROUP BY d.dish_id;

-- Crea nuova prenotazione
INSERT INTO reservations (total_amount, date_time, ready, picked_up, user_id)
VALUES (15.50, '2025-12-15 12:30:00', FALSE, FALSE, 1);  -- user_id dall'utente loggato

-- Inserisci piatti nella prenotazione
INSERT INTO reservation_dishes (reservation_id, dish_id, quantity)
VALUES 
    (LAST_INSERT_ID(), 1, 2),  -- 2x Pasta al Pomodoro
    (LAST_INSERT_ID(), 5, 1),  -- 1x Cotoletta
    (LAST_INSERT_ID(), 8, 1);  -- 1x Insalata

-- Aggiorna stock dei piatti ordinati
UPDATE dishes SET stock = stock - 2 WHERE dish_id = 1;  -- decrementa di quantità ordinata
UPDATE dishes SET stock = stock - 1 WHERE dish_id = 5;
UPDATE dishes SET stock = stock - 1 WHERE dish_id = 8;

-- =====================================================
-- 4. ADMIN - Gestione Prenotazioni (admin-bookings.html)
-- =====================================================

-- Lista tutte le prenotazioni con stato e informazioni utente
SELECT
    r.reservation_id,
    r.date_time,
    r.total_amount,
    u.user_id,
    u.first_name,
    u.last_name,
    u.email,
    CASE
        WHEN r.status = 'Completato' THEN 'Completato'
        WHEN r.status = 'Pronto al ritiro' THEN 'Pronto al ritiro'
        ELSE 'In preparazione'
    END AS status,
    COUNT(DISTINCT rd.dish_id) AS num_dishes
FROM reservations r
JOIN reservation_dishes rd ON rd.reservation_id = r.reservation_id
JOIN users u ON r.user_id = u.user_id
GROUP BY u.user_id, r.date_time
ORDER BY r.date_time DESC;



-- Prenotazioni in preparazione (per la dashboard admin)
SELECT r.reservation_id, r.date_time, r.total_amount,
       u.first_name, u.last_name, u.phone_number
FROM reservations r
JOIN users u ON r.user_id = u.user_id
WHERE r.ready = FALSE AND r.picked_up = FALSE
ORDER BY r.date_time;

-- Prenotazioni pronte ma non ritirate (per alert/notifiche)
SELECT r.reservation_id, r.date_time, r.total_amount,
       u.first_name, u.last_name, u.phone_number
FROM reservations r
JOIN users u ON r.user_id = u.user_id
WHERE r.ready = TRUE AND r.picked_up = FALSE
ORDER BY r.date_time;

-- Dettaglio completo di una prenotazione (per modale o pagina dettaglio)
SELECT r.reservation_id, r.date_time, r.total_amount, r.ready, r.picked_up,
       u.first_name, u.last_name, u.email, u.phone_number,
       d.name AS dish_name, d.price, rd.quantity,
       (rd.quantity * d.price) AS subtotal
FROM reservations r
JOIN users u ON r.user_id = u.user_id
JOIN reservation_dishes rd ON r.reservation_id = rd.reservation_id
JOIN dishes d ON rd.dish_id = d.dish_id
WHERE r.reservation_id = 1  -- sostituire con l'ID prenotazione
ORDER BY d.name;

-- Marca prenotazione come pronta
UPDATE reservations
SET ready = TRUE
WHERE reservation_id = 1;

-- Marca prenotazione come ritirata
UPDATE reservations
SET picked_up = TRUE
WHERE reservation_id = 1;

-- Prenotazioni di oggi
SELECT r.reservation_id, r.date_time, r.total_amount, r.ready, r.picked_up,
       u.first_name, u.last_name, u.phone_number
FROM reservations r
JOIN users u ON r.user_id = u.user_id
WHERE DATE(r.date_time) = CURDATE()
ORDER BY r.date_time;

-- =====================================================
-- 5. USER - Dashboard e Storico Prenotazioni
-- =====================================================

-- Prenotazioni dell'utente loggato (user-dashboard.html)
SELECT r.reservation_id, r.date_time, r.total_amount, r.ready, r.picked_up,
       CASE 
         WHEN r.picked_up = TRUE THEN 'Completata'
         WHEN r.ready = TRUE THEN 'Pronta per il ritiro'
         ELSE 'In preparazione'
       END AS status
FROM reservations r
WHERE r.user_id = 1  -- sostituire con l'ID dell'utente loggato
ORDER BY r.date_time DESC;

-- Prenotazioni future dell'utente
SELECT r.reservation_id, r.date_time, r.total_amount, r.ready, r.picked_up
FROM reservations r
WHERE r.user_id = 1  -- ID utente loggato
  AND r.date_time >= NOW()
  AND r.picked_up = FALSE
ORDER BY r.date_time;

-- Storico prenotazioni completate
SELECT r.reservation_id, r.date_time, r.total_amount
FROM reservations r
WHERE r.user_id = 1  -- ID utente loggato
  AND r.picked_up = TRUE
ORDER BY r.date_time DESC
LIMIT 10;

-- Dettaglio prenotazione utente con piatti ordinati
SELECT r.reservation_id, r.date_time, r.total_amount, r.ready, r.picked_up,
       d.name AS dish_name, d.price, rd.quantity,
       (rd.quantity * d.price) AS subtotal
FROM reservations r
JOIN reservation_dishes rd ON r.reservation_id = rd.reservation_id
JOIN dishes d ON rd.dish_id = d.dish_id
WHERE r.reservation_id = 1  -- ID prenotazione
  AND r.user_id = 1  -- ID utente loggato (per sicurezza)
ORDER BY d.name;

-- Conta prenotazioni attive dell'utente
SELECT COUNT(*) AS active_bookings
FROM reservations
WHERE user_id = 1  -- ID utente loggato
  AND picked_up = FALSE;

-- =====================================================
-- 6. USER PROFILE - Profilo e Preferenze (user-profile.html)
-- =====================================================

-- Recupera informazioni utente
SELECT user_id, email, first_name, last_name, phone_number, admin
FROM users
WHERE user_id = 1;  -- ID utente loggato

-- Recupera specifiche dietetiche dell'utente
SELECT ds.dietary_spec_id, ds.dietary_spec_name
FROM user_specifications us
JOIN dietary_specifications ds ON us.dietary_spec_id = ds.dietary_spec_id
WHERE us.user_id = 1  -- ID utente loggato
ORDER BY ds.dietary_spec_name;

-- Aggiorna dati utente
UPDATE users
SET first_name = 'Mario',
    last_name = 'Rossi',
    phone_number = '3331234567'
WHERE user_id = 1;

-- Elimina tutte le specifiche dietetiche dell'utente (prima di reinserire)
DELETE FROM user_specifications WHERE user_id = 1;

-- Inserisci nuove specifiche dietetiche
INSERT INTO user_specifications (user_id, dietary_spec_id)
VALUES 
    (1, 1),  -- Vegetariano
    (1, 3);  -- Senza Lattosio

-- Cambia password
UPDATE users
SET password = '$2y$10$...'  -- password hashata con password_hash()
WHERE user_id = 1;

-- Verifica email univoca (per aggiornamento email)
SELECT COUNT(*) AS email_exists
FROM users
WHERE email = 'nuova@email.com'
  AND user_id != 1;  -- esclude l'utente corrente

-- Piatti raccomandati basati su specifiche dietetiche utente
SELECT DISTINCT d.dish_id, d.name, d.description, d.price, d.stock, d.image, c.category_name
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
WHERE d.stock > 0
  AND NOT EXISTS (
    -- Escludi piatti che contengono specifiche NON compatibili con l'utente
    SELECT 1 
    FROM dish_specifications dsp
    WHERE dsp.dish_id = d.dish_id
    AND dsp.dietary_spec_id NOT IN (
        SELECT dietary_spec_id 
        FROM user_specifications 
        WHERE user_id = 1
    )
  )
ORDER BY c.category_name, d.name;

-- =====================================================
-- 7. LOGIN E REGISTRAZIONE (login.html, register.html)
-- =====================================================

-- Verifica login utente (confrontare password con password_verify in PHP)
SELECT user_id, email, password, first_name, last_name, admin, phone_number
FROM users
WHERE email = 'mario.rossi@example.com';  -- email inserita nel form

-- Registrazione nuovo utente
INSERT INTO users (email, password, first_name, last_name, admin, phone_number)
VALUES (
    'mario.rossi@example.com',
    '$2y$10$...',  -- password hashata con password_hash()
    'Mario',
    'Rossi',
    FALSE,
    '3331234567'
);

-- Verifica se email esiste già
SELECT COUNT(*) AS email_exists
FROM users
WHERE email = 'mario.rossi@example.com';

-- =====================================================
-- 8. STATISTICHE E DASHBOARD ADMIN
-- =====================================================

-- Statistiche generali per admin-dashboard.html
SELECT 
    (SELECT COUNT(*) FROM reservations WHERE DATE(date_time) = CURDATE()) AS today_orders,
    (SELECT COUNT(*) FROM reservations WHERE ready = FALSE AND picked_up = FALSE) AS pending_orders,
    (SELECT SUM(total_amount) FROM reservations WHERE DATE(date_time) = CURDATE()) AS today_revenue,
    (SELECT COUNT(*) FROM dishes WHERE stock < 10) AS low_stock_items;

-- Piatti più venduti (Top 3)
SELECT d.name, d.price, SUM(rd.quantity) AS total_sold, 
       SUM(rd.quantity * d.price) AS revenue,
       c.category_name
FROM dishes d
JOIN reservation_dishes rd ON d.dish_id = rd.dish_id
JOIN categories c ON d.category_id = c.category_id
GROUP BY d.dish_id, d.name, d.price, c.category_name
ORDER BY total_sold DESC
LIMIT 3;

-- Fatturato per categoria
SELECT c.category_name, 
       COUNT(DISTINCT r.reservation_id) AS num_orders,
       SUM(rd.quantity * d.price) AS total_revenue
FROM categories c
JOIN dishes d ON c.category_id = d.category_id
JOIN reservation_dishes rd ON d.dish_id = rd.dish_id
JOIN reservations r ON rd.reservation_id = r.reservation_id
GROUP BY c.category_id, c.category_name
ORDER BY total_revenue DESC;

-- Fatturato ultimi 7 giorni
SELECT DATE(date_time) AS order_date, 
       COUNT(*) AS num_orders,
       SUM(total_amount) AS daily_revenue
FROM reservations
WHERE date_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DATE(date_time)
ORDER BY order_date DESC;

-- Piatti con stock basso (alert per admin)
SELECT d.dish_id, d.name, d.stock, c.category_name
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
WHERE d.stock < 10
ORDER BY d.stock ASC;

-- Clienti più attivi
SELECT u.user_id, u.first_name, u.last_name, u.email,
       COUNT(r.reservation_id) AS num_orders,
       SUM(r.total_amount) AS total_spent
FROM users u
JOIN reservations r ON u.user_id = r.user_id
WHERE u.admin = FALSE
GROUP BY u.user_id, u.first_name, u.last_name, u.email
ORDER BY num_orders DESC
LIMIT 10;

-- Media spesa per ordine
SELECT 
    AVG(total_amount) AS avg_order_value,
    MIN(total_amount) AS min_order,
    MAX(total_amount) AS max_order,
    COUNT(*) AS total_orders
FROM reservations;

-- =====================================================
-- 9. QUERY AVANZATE E REPORT
-- =====================================================

-- Prenotazioni del mese corrente
SELECT r.reservation_id, r.date_time, r.total_amount, r.ready, r.picked_up,
       u.first_name, u.last_name, u.email
FROM reservations r
JOIN users u ON r.user_id = u.user_id
WHERE MONTH(r.date_time) = MONTH(CURRENT_DATE())
  AND YEAR(r.date_time) = YEAR(CURRENT_DATE())
ORDER BY r.date_time DESC;

-- Piatti mai ordinati
SELECT d.dish_id, d.name, d.price, d.stock, c.category_name
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
LEFT JOIN reservation_dishes rd ON d.dish_id = rd.dish_id
WHERE rd.dish_id IS NULL
ORDER BY c.category_name, d.name;

-- Calcola totale calorie per una prenotazione
SELECT r.reservation_id, r.user_id, SUM(d.calories * rd.quantity) AS total_calories
FROM reservations r
JOIN reservation_dishes rd ON r.reservation_id = rd.reservation_id
JOIN dishes d ON rd.dish_id = d.dish_id
WHERE r.reservation_id = 1  -- sostituire con l'ID prenotazione
GROUP BY r.reservation_id, r.user_id;

-- Tasso di completamento prenotazioni
SELECT 
    COUNT(*) AS total_reservations,
    SUM(CASE WHEN picked_up = TRUE THEN 1 ELSE 0 END) AS completed,
    SUM(CASE WHEN ready = TRUE AND picked_up = FALSE THEN 1 ELSE 0 END) AS ready_not_picked,
    SUM(CASE WHEN ready = FALSE THEN 1 ELSE 0 END) AS in_preparation,
    ROUND(SUM(CASE WHEN picked_up = TRUE THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS completion_rate_percent
FROM reservations;

-- Orari di punta (ore con più prenotazioni)
SELECT 
    HOUR(date_time) AS hour_slot,
    COUNT(*) AS num_reservations,
    SUM(total_amount) AS revenue
FROM reservations
GROUP BY HOUR(date_time)
ORDER BY num_reservations DESC;

-- Confronto vendite settimana corrente vs precedente
SELECT 
    'Settimana Corrente' AS period,
    COUNT(*) AS orders,
    SUM(total_amount) AS revenue
FROM reservations
WHERE YEARWEEK(date_time, 1) = YEARWEEK(CURDATE(), 1)
UNION ALL
SELECT 
    'Settimana Precedente' AS period,
    COUNT(*) AS orders,
    SUM(total_amount) AS revenue
FROM reservations
WHERE YEARWEEK(date_time, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1);

-- Piatti più richiesti per fascia oraria
SELECT 
    CASE 
        WHEN HOUR(r.date_time) BETWEEN 12 AND 13 THEN '12:00-13:00'
        WHEN HOUR(r.date_time) BETWEEN 13 AND 14 THEN '13:00-14:00'
        ELSE 'Altro'
    END AS time_slot,
    d.name,
    SUM(rd.quantity) AS total_quantity
FROM reservations r
JOIN reservation_dishes rd ON r.reservation_id = rd.reservation_id
JOIN dishes d ON rd.dish_id = d.dish_id
GROUP BY time_slot, d.dish_id, d.name
ORDER BY time_slot, total_quantity DESC;

-- =====================================================
-- 10. UTILITY E MANUTENZIONE
-- =====================================================

-- Reset stock giornaliero (da eseguire ogni giorno)
UPDATE dishes SET stock = 50 WHERE category_id = 1;  -- Primi Piatti
UPDATE dishes SET stock = 40 WHERE category_id = 2;  -- Secondi Piatti
UPDATE dishes SET stock = 30 WHERE category_id = 3;  -- Contorni

-- Backup dati piatti (crea snapshot)
CREATE TABLE dishes_backup AS SELECT * FROM dishes;

-- Elimina prenotazioni vecchie (più di 6 mesi)
DELETE FROM reservation_dishes 
WHERE reservation_id IN (
    SELECT reservation_id FROM reservations 
    WHERE date_time < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
);
DELETE FROM reservations 
WHERE date_time < DATE_SUB(CURDATE(), INTERVAL 6 MONTH);

-- Verifica integrità dati
-- Trova prenotazioni senza piatti
SELECT r.reservation_id, r.date_time, r.total_amount
FROM reservations r
LEFT JOIN reservation_dishes rd ON r.reservation_id = rd.reservation_id
WHERE rd.reservation_id IS NULL;

-- Trova piatti con stock negativo (errore)
SELECT dish_id, name, stock
FROM dishes
WHERE stock < 0;

-- Lista utenti inattivi (nessuna prenotazione negli ultimi 3 mesi)
SELECT u.user_id, u.email, u.first_name, u.last_name,
       MAX(r.date_time) AS last_reservation
FROM users u
LEFT JOIN reservations r ON u.user_id = r.user_id
WHERE u.admin = FALSE
GROUP BY u.user_id, u.email, u.first_name, u.last_name
HAVING MAX(r.date_time) < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
   OR MAX(r.date_time) IS NULL;

-- Ricalcola total_amount per prenotazioni (verifica consistenza)
SELECT r.reservation_id, r.total_amount AS stored_total,
       SUM(rd.quantity * d.price) AS calculated_total,
       (r.total_amount - SUM(rd.quantity * d.price)) AS difference
FROM reservations r
JOIN reservation_dishes rd ON r.reservation_id = rd.reservation_id
JOIN dishes d ON rd.dish_id = d.dish_id
GROUP BY r.reservation_id, r.total_amount
HAVING ABS(difference) > 0.01;  -- differenza maggiore di 1 centesimo

-- Esporta menu del giorno (per stampa o PDF)
SELECT c.category_name, d.name, d.description, d.price, d.calories,
       GROUP_CONCAT(ds.dietary_spec_name SEPARATOR ', ') AS dietary_info,
       d.stock
FROM dishes d
JOIN categories c ON d.category_id = c.category_id
LEFT JOIN dish_specifications dsp ON d.dish_id = dsp.dish_id
LEFT JOIN dietary_specifications ds ON dsp.dietary_spec_id = ds.dietary_spec_id
WHERE d.stock > 0
GROUP BY d.dish_id, c.category_name, d.name, d.description, d.price, d.calories, d.stock
ORDER BY 
    CASE c.category_name
        WHEN 'Primi Piatti' THEN 1
        WHEN 'Secondi Piatti' THEN 2
        WHEN 'Contorni' THEN 3
        WHEN 'Dolci' THEN 4
        WHEN 'Bevande' THEN 5
        ELSE 6
    END, d.name;

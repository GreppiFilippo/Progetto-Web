-- Script di inserimento dati per il database della mensa
USE cafeteria;

-- Disabilita i controlli delle chiavi esterne per l'inserimento
SET foreign_key_checks = 0;

-- Inserimento categorie
INSERT INTO categories (category_id, category_name) VALUES
(1, 'Primi'),
(2, 'Secondi'),
(3, 'Contorni'),
(4, 'Dessert');

-- Inserimento specifiche dietetiche
INSERT INTO dietary_specifications (dietary_spec_id, dietary_spec_name) VALUES
(1, 'Vegetariano'),
(2, 'Vegano'),
(3, 'Senza glutine'),
(4, 'Senza lattosio');

-- Inserimento utenti
INSERT INTO users (user_id, email, password, first_name, last_name, admin, registration_date) VALUES
(1, 'admin@mensa.it', 'admin123', 'Admin', 'Sistema', TRUE,  '2024-10-01 09:00:00'),
(2, 'mario.rossi@studenti.it', 'mario123', 'Mario', 'Rossi', FALSE, '2024-10-15 10:30:00'),
(3, 'giulia.verdi@studenti.it', 'giulia123', 'Giulia', 'Verdi', FALSE, '2024-10-18 11:15:00'),
(4, 'luca.bianchi@studenti.it', 'luca123', 'Luca', 'Bianchi', FALSE, '2024-10-20 12:00:00'),
(5, 'anna.neri@studenti.it', 'anna123', 'Anna', 'Neri', FALSE, '2024-10-22 09:45:00');

-- Inserimento piatti
INSERT INTO dishes (dish_id, name, description, price, stock, image, calories, category_id) VALUES
(1, 'Pasta al Pomodoro', 'Pasta con salsa di pomodoro fresco, basilico e parmigiano', 4.50, 25, 'pasta-pomodoro.svg', 350, 1),
(2, 'Risotto ai Funghi', 'Risotto cremoso con funghi porcini e parmigiano', 5.00, 18, 'risotto.jpg', 420, 1),
(3, 'Lasagne alla Bolognese', 'Lasagne tradizionali con ragù di carne e besciamella', 5.50, 3,  'lasagne.png', 580, 1),
(4, 'Pollo alla Griglia', 'Petto di pollo grigliato con erbe aromatiche', 6.00, 30, 'pollo-alla-griglia.webp', 280, 2),
(5, 'Hamburger Vegetale', 'Burger vegetale con insalata, pomodoro e salse', 5.50, 15, 'veggie-burger.jpg', 380, 2),
(6, 'Cotoletta alla Milanese', 'Cotoletta impanata e fritta servita con limone', 6.50, 0,  'cotoletta.png', 520, 2),
(7, 'Insalata Mista', 'Insalata fresca con pomodori, carote e mais', 2.50, 40, 'insalata.webp', 80,  3),
(8, 'Patate al Forno', 'Patate dorate al forno con rosmarino', 2.00, 35, 'patate.webp', 180, 3),
(9, 'Tiramisù', 'Classico tiramisù con savoiardi e mascarpone', 3.00, 20, 'tiramisu.jpg', 320, 4),
(10, 'Frutta di Stagione', 'Macedonia di frutta fresca di stagione', 2.50, 45, 'macedonia-di-frutta.jpg', 120, 4);

-- Inserimento specifiche dietetiche per i piatti
INSERT INTO dish_specifications (dish_id, dietary_spec_id) VALUES
(1, 1),  -- Pasta al Pomodoro - Vegetariano
(2, 1),  -- Risotto ai Funghi - Vegetariano
(4, 3),  -- Pollo alla Griglia - Senza glutine
(5, 2),  -- Hamburger Vegetale - Vegano
(7, 2),  -- Insalata Mista - Vegano
(8, 1),  -- Patate al Forno - Vegetariano
(9, 1),  -- Tiramisù - Vegetariano
(10, 2); -- Frutta di Stagione - Vegano

-- Inserimento specifiche dietetiche per gli utenti (esempi)
INSERT INTO user_specifications (user_id, dietary_spec_id) VALUES
(3, 1),  -- Giulia è vegetariana
(4, 2),  -- Luca è vegano
(5, 3);  -- Anna ha intolleranza al glutine

-- Inserimento prenotazioni di esempio (AGGIORNATO: include notes)
-- NB: ho reso coerenti i total_amount con i piatti inseriti in reservation_dishes
INSERT INTO reservations (reservation_id, total_amount, date_time, notes, status, user_id) VALUES
(1, 12.00, '2024-11-20 12:30:00', 'Ritiro veloce, ho poco tempo.', 'Completato', 2),
(2, 7.00,  '2024-11-20 13:00:00', 'Niente posate, grazie.',        'Pronto al ritiro', 3),
(3, 13.50, '2024-11-21 12:15:00', 'Confezionare tutto da asporto.', 'In Preparazione', 4),
(4, 8.50,  '2024-11-21 12:45:00', 'Allergia: evitare contaminazioni.', 'Annullato', 5),
(5, 10.50, '2024-11-21 13:30:00', 'Se possibile, patate ben cotte.', 'Da Visualizzare', 2);

-- ==========================
-- 5 NUOVI ORDINI PER LUCA (user_id=4)
-- almeno 3: ieri/oggi/domani rispetto a oggi 2025-12-17
-- ==========================
INSERT INTO reservations (reservation_id, total_amount, date_time, notes, status, user_id) VALUES
(6, 7.50,  '2025-12-16 12:20:00', 'Senza salse aggiuntive.',               'Completato', 4),        -- IERI
(7, 32.50, '2025-12-17 13:05:00', 'Ordine grande: tutto ben confezionato.', 'In Preparazione', 4),  -- OGGI (multi piatti + qta 2/3)
(8, 15.00, '2025-12-18 12:50:00', 'Ritiro alle 13:00 spaccate.',           'Da Visualizzare', 4),  -- DOMANI
(9, 16.50, '2025-12-19 12:10:00', 'Aggiungere tovaglioli.',                'Da Visualizzare', 4),
(10, 10.50,'2025-12-10 13:25:00', 'Se manca qualcosa chiamatemi.',         'Completato', 4);

-- Inserimento dettagli prenotazioni
INSERT INTO reservation_dishes (reservation_id, dish_id, quantity) VALUES
-- Prenotazione 1: Mario - Pasta al Pomodoro + Insalata Mista + Frutta di Stagione (x2)
(1, 1, 1),
(1, 7, 1),
(1, 10, 2),

-- Prenotazione 2: Giulia - Risotto ai Funghi + Patate al Forno
(2, 2, 1),
(2, 8, 1),

-- Prenotazione 3: Luca - Hamburger Vegetale + Insalata Mista + Frutta di Stagione + Tiramisù
(3, 5, 1),
(3, 7, 1),
(3, 10, 1),
(3, 9, 1),

-- Prenotazione 4: Anna - Pollo alla Griglia + Insalata Mista
(4, 4, 1),
(4, 7, 1),

-- Prenotazione 5: Mario - Lasagne alla Bolognese + Patate al Forno + Tiramisù
(5, 3, 1),
(5, 8, 1),
(5, 9, 1),

-- ==========================
-- NUOVI DETTAGLI ORDINI LUCA (6..10)
-- ==========================

-- Prenotazione 6 (IERI): Luca - Hamburger Vegetale + Patate al Forno
(6, 5, 1),
(6, 8, 1),

-- Prenotazione 7 (OGGI): ordine con più piatti e quantità tutte doppie o triple
(7, 5, 3),   -- Hamburger Vegetale x3
(7, 7, 2),   -- Insalata Mista x2
(7, 10, 2),  -- Frutta di Stagione x2
(7, 8, 3),   -- Patate al Forno x3

-- Prenotazione 8 (DOMANI): Risotto ai Funghi x2 + Insalata Mista x2
(8, 2, 2),
(8, 7, 2),

-- Prenotazione 9: Pasta al Pomodoro x2 + Frutta di Stagione x3
(9, 1, 2),
(9, 10, 3),

-- Prenotazione 10: Pollo alla Griglia + Patate al Forno + Frutta di Stagione
(10, 4, 1),
(10, 8, 1),
(10, 10, 1);


-- Riabilita i controlli delle chiavi esterne
SET foreign_key_checks = 1;

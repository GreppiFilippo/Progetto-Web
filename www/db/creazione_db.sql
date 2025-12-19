CREATE DATABASE IF NOT EXISTS cafeteria;
USE cafeteria;

-- =========================
-- CATEGORIES
-- =========================
CREATE TABLE categories (
    category_id INT NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- DIETARY SPECIFICATIONS
-- =========================
CREATE TABLE dietary_specifications (
    dietary_spec_id INT NOT NULL AUTO_INCREMENT,
    dietary_spec_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (dietary_spec_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- USERS
-- =========================
CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    admin BOOLEAN NOT NULL DEFAULT FALSE,
    registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    UNIQUE KEY uk_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- DISHES
-- =========================
CREATE TABLE dishes (
    dish_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255) NOT NULL,
    price DECIMAL(6,2) NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    calories INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (dish_id),
    CONSTRAINT fk_dishes_category
        FOREIGN KEY (category_id)
        REFERENCES categories(category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- RESERVATIONS
-- =========================
CREATE TABLE reservations (
    reservation_id INT NOT NULL AUTO_INCREMENT,
    total_amount DECIMAL(8,2) NOT NULL,
    date_time DATETIME NOT NULL,
    notes TEXT COMMENT 'Note aggiuntive inserite dall’utente',
    status ENUM(
        'Da Visualizzare',
        'In Preparazione',
        'Pronto al ritiro',
        'Completato',
        'Annullato'
    ) NOT NULL DEFAULT 'Da Visualizzare',
    user_id INT NOT NULL,
    PRIMARY KEY (reservation_id),
    CONSTRAINT fk_reservations_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- RESERVATION - DISHES (N:M)
-- =========================
CREATE TABLE reservation_dishes (
    reservation_id INT NOT NULL,
    dish_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (reservation_id, dish_id),
    CONSTRAINT fk_reservation_dishes_reservation
        FOREIGN KEY (reservation_id)
        REFERENCES reservations(reservation_id),
    CONSTRAINT fk_reservation_dishes_dish
        FOREIGN KEY (dish_id)
        REFERENCES dishes(dish_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- TIME SLOTS (AVAILABLE RESERVATION SLOTS)
-- =========================
CREATE TABLE time_slots (
    slot_date DATE NOT NULL,
    slot_time TIME NOT NULL,
    PRIMARY KEY (slot_date, slot_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- USER - DIETARY SPECIFICATIONS (N:M)
-- =========================
CREATE TABLE user_specifications (
    user_id INT NOT NULL,
    dietary_spec_id INT NOT NULL,
    PRIMARY KEY (user_id, dietary_spec_id),
    CONSTRAINT fk_user_specifications_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id),
    CONSTRAINT fk_user_specifications_dietary
        FOREIGN KEY (dietary_spec_id)
        REFERENCES dietary_specifications(dietary_spec_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- DISH - DIETARY SPECIFICATIONS (N:M)
-- =========================
CREATE TABLE dish_specifications (
    dish_id INT NOT NULL,
    dietary_spec_id INT NOT NULL,
    PRIMARY KEY (dish_id, dietary_spec_id),
    CONSTRAINT fk_dish_specifications_dish
        FOREIGN KEY (dish_id)
        REFERENCES dishes(dish_id),
    CONSTRAINT fk_dish_specifications_dietary
        FOREIGN KEY (dietary_spec_id)
        REFERENCES dietary_specifications(dietary_spec_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 1) (Opzionale) crea tabella metadata
CREATE TABLE IF NOT EXISTS time_slots_meta (
  id TINYINT PRIMARY KEY DEFAULT 1,
  generated_until DATE NOT NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2) Procedure che popola per N giorni gli slot 11:30 -> 15:45 ogni 15 min
DELIMITER $$
CREATE PROCEDURE populate_time_slots(IN p_days INT)
BEGIN
  DECLARE i INT DEFAULT 0;
  DECLARE slot_date DATE;
  DECLARE t TIME;
  DECLARE end_time TIME DEFAULT '15:45:00';

  IF p_days <= 0 THEN
    SET p_days = 14;
  END IF;

  WHILE i < p_days DO
    SET slot_date = DATE_ADD(CURDATE(), INTERVAL i DAY);
    SET t = '11:30:00';
    WHILE t <= end_time DO
      INSERT IGNORE INTO time_slots (slot_date, slot_time) VALUES (slot_date, t);
      SET t = ADDTIME(t, '00:15:00');
    END WHILE;
    SET i = i + 1;
  END WHILE;

  REPLACE INTO time_slots_meta (id, generated_until, updated_at)
    VALUES (1, DATE_ADD(CURDATE(), INTERVAL p_days-1 DAY), NOW());
END$$
DELIMITER ;

-- 3) Abilita scheduler (necessario privilegi GLOBAL)
SET GLOBAL event_scheduler = ON;

-- 4) Evento giornaliero che mantiene la finestra (14 giorni)
CREATE EVENT IF NOT EXISTS ev_populate_time_slots_daily
ON SCHEDULE EVERY 1 DAY
DO
  CALL populate_time_slots(14);

-- 5) Esegui subito per popolare ora (opzionale)
CALL populate_time_slots(14);
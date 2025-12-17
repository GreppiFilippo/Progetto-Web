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

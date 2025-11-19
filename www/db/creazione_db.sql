CREATE DATABASE cafeteria_logical;
USE cafeteria_logical;

CREATE TABLE categories (
    category_id INT NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL,
    CONSTRAINT pk_categories PRIMARY KEY (category_id)
);

CREATE TABLE dietary_specifications (
    dietary_spec_id INT NOT NULL AUTO_INCREMENT,
    dietary_spec_name VARCHAR(50) NOT NULL,
    CONSTRAINT pk_dietary_specifications PRIMARY KEY (dietary_spec_id)
);

CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    admin BOOLEAN NOT NULL,
    phone_number CHAR(10) NOT NULL,
    CONSTRAINT pk_users PRIMARY KEY (user_id),
    CONSTRAINT uq_users_email UNIQUE (email)
);

CREATE TABLE dishes (
    dish_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255) NOT NULL,
    price DECIMAL(6,2) NOT NULL,
    availability INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    calories INT NOT NULL,
    category_id INT NOT NULL,
    CONSTRAINT pk_dishes PRIMARY KEY (dish_id),
    CONSTRAINT fk_dishes_category FOREIGN KEY (category_id)
        REFERENCES categories(category_id)
);

CREATE TABLE reservations (
    reservation_id INT NOT NULL AUTO_INCREMENT,
    total_amount DECIMAL(8,2) NOT NULL,
    date_time DATETIME NOT NULL,
    ready BOOLEAN NOT NULL,
    picked_up BOOLEAN NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT pk_reservations PRIMARY KEY (reservation_id),
    CONSTRAINT fk_reservations_user FOREIGN KEY (user_id)
        REFERENCES users(user_id)
);

CREATE TABLE reservation_dishes (
    reservation_id INT NOT NULL,
    dish_id INT NOT NULL,
    quantity INT NOT NULL,
    CONSTRAINT pk_reservation_dishes PRIMARY KEY (reservation_id, dish_id),
    CONSTRAINT fk_reservation_dishes_dish FOREIGN KEY (dish_id)
        REFERENCES dishes(dish_id),
    CONSTRAINT fk_reservation_dishes_reservation FOREIGN KEY (reservation_id)
        REFERENCES reservations(reservation_id)
);

CREATE TABLE user_specifications (
    user_id INT NOT NULL,
    dietary_spec_id INT NOT NULL,
    CONSTRAINT pk_user_specifications PRIMARY KEY (dietary_spec_id, user_id),
    CONSTRAINT fk_user_specifications_user FOREIGN KEY (user_id)
        REFERENCES users(user_id),
    CONSTRAINT fk_user_specifications_dietary FOREIGN KEY (dietary_spec_id)
        REFERENCES dietary_specifications(dietary_spec_id)
);

CREATE TABLE dish_specifications (
    dish_id INT NOT NULL,
    dietary_spec_id INT NOT NULL,
    CONSTRAINT pk_dish_specifications PRIMARY KEY (dish_id, dietary_spec_id),
    CONSTRAINT fk_dish_specifications_dish FOREIGN KEY (dish_id)
        REFERENCES dishes(dish_id),
    CONSTRAINT fk_dish_specifications_dietary FOREIGN KEY (dietary_spec_id)
        REFERENCES dietary_specifications(dietary_spec_id)
);

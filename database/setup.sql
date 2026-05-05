CREATE DATABASE IF NOT EXISTS festivo_db;
USE festivo_db;

CREATE TABLE customer (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    username VARCHAR(100) UNIQUE,
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    phone VARCHAR(20),
    age INT
);

CREATE TABLE service_provider (
    provider_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    email VARCHAR(150),
    phone VARCHAR(20)
);

CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY
    name VARCHAR(150),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
);

CREATE TABLE event_type (
    event_type_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

-- UPDATED ONLY HERE (added provider_id FK)
CREATE TABLE service (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT,
    name VARCHAR(150),
    type VARCHAR(100),
    quantity INT,
    photo VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',

    FOREIGN KEY (provider_id) REFERENCES service_provider(provider_id)
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    event_type_id INT,
    event_date DATE,
    duration VARCHAR(50),
    start_time DATETIME,
    end_time DATETIME,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    total_price DECIMAL(10,2),
    location VARCHAR(255),

    FOREIGN KEY (customer_id) REFERENCES customer(customer_id),
    FOREIGN KEY (event_type_id) REFERENCES event_type(event_type_id)
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'wallet') NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    paid_at DATETIME,

    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT,
    category_id INT,
    name VARCHAR(150) NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    available_quantity INT DEFAULT 0,
    description TEXT,
    is_rentable BOOLEAN DEFAULT TRUE,

    FOREIGN KEY (provider_id) REFERENCES service_provider(provider_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE product_colors (
    color_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    color_name VARCHAR(50),

    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price_per_unit DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE rental_pricing (
    pricing_id INT AUTO_INCREMENT PRIMARY KEY,
    min_hours INT NOT NULL,
    max_hours INT NOT NULL,
    multiplier DECIMAL(4,2) NOT NULL
);

INSERT INTO rental_pricing (min_hours, max_hours, multiplier) VALUES
(1, 3, 1.00),
(4, 6, 0.80),
(7, 9, 0.68),
(10, 12, 0.60);
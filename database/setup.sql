CREATE DATABASE IF NOT EXISTS festivo_db;
USE festivo_db;
CREATE TABLE IF NOT EXISTS customer (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    username VARCHAR(100) UNIQUE,
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    phone VARCHAR(20),
    age INT
);
CREATE TABLE IF NOT EXISTS service_provider (
    provider_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    email VARCHAR(150),
    phone VARCHAR(20)
);
CREATE TABLE IF NOT EXISTS admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY
);
CREATE TABLE IF NOT EXISTS event_type (
    event_type_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);
CREATE TABLE IF NOT EXISTS service (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    type VARCHAR(100),
    quantity INT,
    photo VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS booking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    event_type_id INT,
    event_date DATE,
    duration VARCHAR(50),
    total_price DECIMAL(10,2),
    status VARCHAR(50),

    FOREIGN KEY (customer_id) REFERENCES customer(customer_id),
    FOREIGN KEY (event_type_id) REFERENCES event_type(event_type_id)
);
CREATE TABLE IF NOT EXISTS booking_service (
    booking_id INT,
    service_id INT,

    PRIMARY KEY (booking_id, service_id),

    FOREIGN KEY (booking_id) REFERENCES booking(booking_id),
    FOREIGN KEY (service_id) REFERENCES service(service_id)
);
CREATE TABLE IF NOT EXISTS payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    name VARCHAR(150),
    address VARCHAR(255),
    payment_info VARCHAR(255),
    status VARCHAR(50),

    FOREIGN KEY (booking_id) REFERENCES booking(booking_id)
);
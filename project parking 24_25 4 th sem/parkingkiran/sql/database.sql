-- database.sql
CREATE DATABASE parking_db;
USE parking_db;

-- Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

-- Admins
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

-- Cities
CREATE TABLE cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

-- Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

-- Locations
CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    address TEXT,
    city_id INT,
    category_id INT,
    latitude VARCHAR(50),
    longitude VARCHAR(50),
    FOREIGN KEY(city_id) REFERENCES cities(id),
    FOREIGN KEY(category_id) REFERENCES categories(id)
);

-- Slots
CREATE TABLE slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slot_number VARCHAR(20),
    location_id INT,
    type VARCHAR(50),
    is_booked BOOLEAN DEFAULT FALSE,
    FOREIGN KEY(location_id) REFERENCES locations(id)
);

-- Bookings
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    slot_id INT,
    booking_date DATE,
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(slot_id) REFERENCES slots(id)
);

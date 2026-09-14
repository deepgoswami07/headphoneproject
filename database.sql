-- ============================================
-- Soundphere Database Schema
-- Import this file in phpMyAdmin OR run:
--   mysql -u root -p < database.sql
-- ============================================

CREATE DATABASE IF NOT EXISTS soundphere;
USE soundphere;

-- ---------- D1: Users ----------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(10) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------- D2: Products ----------
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    old_price INT NOT NULL,
    description TEXT,
    image1 VARCHAR(255),
    image2 VARCHAR(255),
    image3 VARCHAR(255)
);

-- ---------- D3: Cart ----------
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    color VARCHAR(30) DEFAULT 'Orbit Violet',
    qty INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ---------- D4: Orders ----------
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(15) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    qty INT NOT NULL,
    color VARCHAR(30),
    customer_name VARCHAR(50) NOT NULL,
    phone VARCHAR(10) NOT NULL,
    address VARCHAR(150) NOT NULL,
    city VARCHAR(50) NOT NULL,
    pincode VARCHAR(6) NOT NULL,
    state VARCHAR(30) NOT NULL,
    payment_mode VARCHAR(10) DEFAULT 'COD',
    total_amount INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- ---------- Sample product: Aura One ----------
INSERT INTO products (name, price, old_price, description, image1, image2, image3) VALUES
('Aura One', 3499, 4999,
 'Aura One wraps rich, room-filling sound around you with 40mm drivers tuned for warmth and clarity. Adaptive hybrid ANC quiets the world, memory-foam cushions keep it comfortable for full-day listening, and a 45-hour battery means you rarely think about charging.',
 'https://images.pexels.com/photos/3394665/pexels-photo-3394665.jpeg?auto=compress&cs=tinysrgb&w=800',
 'https://images.pexels.com/photos/3394652/pexels-photo-3394652.jpeg?auto=compress&cs=tinysrgb&w=800',
 'https://images.pexels.com/photos/3394648/pexels-photo-3394648.jpeg?auto=compress&cs=tinysrgb&w=800'
);

-- ---------- Demo account for quick testing ----------
-- Email: demo@soundphere.com   Password: demo1234
INSERT INTO users (name, email, phone, password) VALUES
('Demo User', 'demo@soundphere.com', '9876543210', '$2y$10$eKL3jODEICwWsyCqvAkei.7X79lJ47IuokS.gEWph9GpPS1JK6EbC');

<?php
include 'db/db.php';
global $pdo;

// Drop the table if it exists
$pdo->exec("DROP TABLE IF EXISTS offers");
$pdo->exec("DROP TABLE IF EXISTS basket");
$pdo->exec("DROP TABLE IF EXISTS products");
$pdo->exec("DROP TABLE IF EXISTS delivery_roles");

// Create the products table
$pdo->exec("
        CREATE TABLE products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product VARCHAR(255) NOT NULL,
            code VARCHAR(50) NOT NULL UNIQUE,
            price DECIMAL(10, 2) NOT NULL
        )
    ");

// Insert sample data into the products table
$pdo->exec("
        INSERT INTO products (product, code, price) VALUES 
        ('Red Widget', 'R01', 32.95),
        ('Green Widget', 'G01', 24.95),
        ('Blue Widget', 'B01', 7.95)
    ");

// Drop the table if it exists


// Create the delivery_roles table
$pdo->exec("
        CREATE TABLE delivery_roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_price DECIMAL(10, 2) NOT NULL,
            cost DECIMAL(10, 2) NOT NULL
        )
    ");

// Insert data into the delivery_roles table
$pdo->exec("
        INSERT INTO delivery_roles (order_price, cost) VALUES 
        (50.00, 4.95),
        (90.00, 2.95)
    ");

// Drop the table if it exists


// Create the offers table
$pdo->exec("
        CREATE TABLE offers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            description TEXT NOT NULL,
            product_id INT NOT NULL,
            discount_percentage DECIMAL(5, 2) NOT NULL,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )
    ");

// Insert data into the offers table
$pdo->exec("
        INSERT INTO offers (description, product_id, discount_percentage) VALUES 
        ('Buy one red widget, get the second half price', 1, 50.00)
    ");

// Create the basket table
$pdo->exec("
        CREATE TABLE basket (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            quantities INT NOT NULL,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )
    ");

echo "tables created and data inserted successfully.". PHP_EOL;

?>
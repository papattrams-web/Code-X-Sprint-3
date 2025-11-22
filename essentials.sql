DROP DATABASE IF EXISTS Essentials;
CREATE DATABASE Essentials;
USE Essentials;

CREATE TABLE Users (
    userID INT AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(70) UNIQUE NOT NULL, 
    email VARCHAR(100) UNIQUE NOT NULL, 
    acc_password VARCHAR(255) NOT NULL, 
    phoneNo VARCHAR(15), 
    userType ENUM('customer', 'staff') NOT NULL DEFAULT 'customer'
);

CREATE TABLE Products (
    productID INT AUTO_INCREMENT PRIMARY KEY, 
    productName VARCHAR(150) NOT NULL, 
    info TEXT, 
    price DECIMAL(10,2) NOT NULL, 
    quantity INT NOT NULL,
    category VARCHAR(150) NOT NULL, 
    availability BOOLEAN DEFAULT TRUE, 
    image_url VARCHAR(300)
);

CREATE TABLE Orders (
    orderID INT AUTO_INCREMENT PRIMARY KEY, 
    userID INT NOT NULL, 
    orderDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    orderStatus ENUM('pending', 'ready', 'confirmed', 'completed', 'canceled') DEFAULT 'pending', 
    totalExpense DECIMAL(10,2) NOT NULL, 
    paymentMethod VARCHAR(50),
    FOREIGN KEY(userID) REFERENCES Users(userID)
);
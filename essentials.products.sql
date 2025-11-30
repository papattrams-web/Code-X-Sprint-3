CREATE DATABASE IF NOT EXISTS essentials;
USE essentials;


-- USERS TABLE (updated as requested) --
CREATE TABLE Users (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(70) NOT NULL,
    lastName VARCHAR(70) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    acc_password VARCHAR(100) NOT NULL,
    userType ENUM('customer', 'staff') NOT NULL DEFAULT 'customer'
);


-- PRODUCTS TABLE --
CREATE TABLE IF NOT EXISTS Products (
    productID INT AUTO_INCREMENT PRIMARY KEY,
    productName VARCHAR(255) NOT NULL,
    info TEXT,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    category VARCHAR(50) NOT NULL,
    image_url VARCHAR(500)
);



-- ORDERS TABLE --
CREATE TABLE Orders (
    orderID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    orderStatus ENUM('pending', 'ready', 'confirmed', 'completed', 'canceled') 
        DEFAULT 'pending',
    orderType ENUM('delivery', 'pickup') NOT NULL,
    totalExpense DECIMAL(10,2) NOT NULL,
    deliveryAddress TEXT,
    FOREIGN KEY (userID) REFERENCES Users(userID)
);


-- ORDER ITEMS TABLE --
CREATE TABLE orderItems (
    orderItemID INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT NOT NULL,
    productID INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    unitPrice DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    FOREIGN KEY (productID) REFERENCES Products(productID)
);

-- PAYMENTS TABLE --
CREATE TABLE Payments (
    paymentID INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT NOT NULL,
    paymentMethod ENUM('cash', 'AshesiMealPlan', 'MobileMoney') NOT NULL,
    paymentStatus ENUM('pending', 'failed', 'confirmed', 'refunded') DEFAULT 'pending',
    amount DECIMAL(10,2) NOT NULL,
    transactionID VARCHAR(150),
    FOREIGN KEY (orderID) REFERENCES Orders(orderID)
);

-- STORE INVENTORY TABLE --
CREATE TABLE storeInventory (
    inventoryID INT AUTO_INCREMENT PRIMARY KEY,
    productID INT NOT NULL UNIQUE,
    stockQuantity INT NOT NULL CHECK (stockQuantity >= 0),
    lowStockThreshold INT DEFAULT 10,
    FOREIGN KEY (productID) REFERENCES Products(productID)
);

-- INSERT PRODUCTS --

INSERT INTO Products (productName, info, price, quantity, category, image_url) VALUES
-- Beverages
('Coca Cola 500ml', 'Refreshing carbonated soft drink', 3.50, 50, 'Beverages', 'https://images.unsplash.com/photo-1554866585-cd94860890b7?w=400&h=300&fit=crop'),
('Sprite 500ml', 'Lemon-lime flavored soft drink', 3.50, 45, 'Beverages', 'images/sprite.jpg.jpg'),
('Fanta Orange 500ml', 'Orange flavored carbonated drink', 3.50, 40, 'Beverages', 'images/Fanta.jpg.jpg'),
('Pepsi 500ml', 'Classic cola soft drink', 3.50, 35, 'Beverages', 'images/Pepsi.jpg.jpg'),
('Mineral Water 500ml', 'Pure drinking water', 2.00, 100, 'Beverages', 'images/water.jpg'),
('Orange Juice 1L', 'Fresh orange juice', 8.00, 30, 'Beverages', 'images/orange juice.jpg.jpg'),
('Apple Juice 1L', 'Sweet apple juice', 8.00, 25, 'Beverages', 'images/Apple Juice.jpg.jpg'),
('Energy Drink', 'High energy drink', 5.00, 40, 'Beverages', 'images/Energy Drink.jpg.jpg'),
('Malt Drink', 'Nutritious malt beverage', 4.50, 30, 'Beverages', 'images/wheat_drink.jpg'),
('Chocolate Drink', 'Rich chocolate flavored drink', 4.00, 35, 'Beverages', 'images/Chocolate Drink.jpg.jpg'),

-- Snacks
('Potato Chips', 'Crispy potato chips', 4.50, 60, 'Snacks', 'homepageimages/chips.webp'),
('Plantain Chips', 'Local favorite plantain chips', 5.00, 50, 'Snacks', 'homepageimages/plantainchips.webp'),
('Biscuits', 'Sweet biscuits', 3.00, 80, 'Snacks', 'images/biscuits.jpg.jpg'),
('Cookies', 'Chocolate cookies', 4.00, 70, 'Snacks', 'images/cookies.jpg'),
('Peanuts', 'Roasted peanuts', 3.50, 45, 'Snacks', 'images/Peanuts.jpg.jpg'),
('Crackers', 'Salty crackers', 3.50, 55, 'Snacks', 'images/crackers.jpg.jpg'),
('Popcorn', 'Buttered popcorn', 4.00, 40, 'Snacks', 'images/popcorn.jpg.jpg'),
('Nuts Mix', 'Mixed nuts', 6.00, 35, 'Snacks', 'images/mixed nuts.jpg.jpg'),
('Chocolate Bar', 'Milk chocolate bar', 5.50, 50, 'Snacks', 'images/choco.jpeg'),
('Candy', 'Assorted candies', 2.50, 100, 'Snacks', 'images/candy.jpg.jpg'),

-- Groceries - Canned Goods
('Canned Beans', 'Canned baked beans', 6.50, 40, 'Groceries', 'images/canned beans.jpg'),
('Canned Corn', 'Sweet corn in can', 5.50, 30, 'Groceries', 'images/canned corn.jpg'),
('Canned Sardines', 'Canned sardines in oil', 8.00, 25, 'Groceries', 'images/canned sardine.jpg.jpg'),
('Canned Tuna', 'Canned tuna fish', 9.00, 20, 'Groceries', 'images/canned tuna.jpg.jpg'),

-- Groceries - Packaged Foods
('Instant Noodles', 'Quick instant noodles', 3.50, 80, 'Groceries', 'images/buldak.jpg'),
('Pasta', 'Spaghetti pasta', 7.00, 45, 'Groceries', 'images/pasta.jpg.jpg'),
('Rice 1kg', 'Long grain rice', 12.00, 50, 'Groceries', 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=300&fit=crop'),
('Cereal', 'Breakfast cereal', 15.00, 30, 'Groceries', 'images/cornflakes.jpg.jpg'),
('Oats', 'Rolled oats', 10.00, 25, 'Groceries', 'images/oats.jpg.jpg'),
('Flour 1kg', 'All-purpose flour', 8.00, 40, 'Groceries', 'images/flour.jpg.jpg'),
('Sugar 1kg', 'Granulated sugar', 6.50, 50, 'Groceries', 'images/sugar.jpg.jpg'),
('Salt', 'Table salt', 2.50, 60, 'Groceries', 'images/salt.jpg.jpg'),
('Cooking Oil 1L', 'Vegetable cooking oil', 18.00, 35, 'Groceries', 'images/cooking_oil.jpg'),
('Baking Powder', 'Baking powder', 4.00, 30, 'Groceries', 'images/baking powder.jpg.jpg'),

-- Groceries - Condiments
('Ketchup', 'Tomato ketchup', 5.50, 40, 'Groceries', 'images/ketchup.jpg.jpg'),
('Mayonnaise', 'Creamy mayonnaise', 6.00, 35, 'Groceries', 'images/mayonnaise.jpg.jpg'),
('Hot Sauce', 'Spicy hot sauce', 4.50, 30, 'Groceries', 'images/hot sauce.jpg.jpg'),
('Soy Sauce', 'Light soy sauce', 5.00, 25, 'Groceries', 'images/soy sauce.jpg.jpg'),

-- Toiletries
('Toothpaste', 'Fluoride toothpaste', 8.00, 50, 'Toiletries', 'images/toothpaste.jpg.jpg'),
('Toothbrush', 'Soft bristle toothbrush', 3.50, 60, 'Toiletries', 'images/toothbrush.jpg.jpg'),
('Shampoo', 'Hair shampoo', 12.00, 40, 'Toiletries', 'images/shampoo.jpg.jpg'),
('Conditioner', 'Hair conditioner', 12.00, 35, 'Toiletries', 'images/conditioner.jpg.jpg'),
('Body Soap', 'Bar soap', 5.00, 70, 'Toiletries', 'images/body soap.jpg.jpg'),
('Body Lotion', 'Moisturizing body lotion', 15.00, 30, 'Toiletries', 'homepageimages/bodylotions.webp'),
('Deodorant', 'Antiperspirant deodorant', 10.00, 40, 'Toiletries', 'images/deodorant.jpg.jpg'),
('Toilet Paper', 'Soft toilet paper', 8.50, 50, 'Toiletries', 'images/t_roll.jpg'),
('Tissue Paper', 'Facial tissue', 4.00, 60, 'Toiletries', 'images/Tissue Paper.jpg.jpg'),
('Hand Sanitizer', 'Alcohol-based sanitizer', 6.00, 45, 'Toiletries', 'images/handsanitizer.jpg.jpg'),
('Razor', 'Disposable razor', 5.50, 40, 'Toiletries', 'images/razor.jpg.jpg'),
('Shaving Cream', 'Foaming shaving cream', 7.00, 30, 'Toiletries', 'images/shaving cream.jpg.jpg'),

-- Cleaning Supplies
('Dish Soap', 'Liquid dishwashing soap', 6.50, 40, 'Groceries', 'images/Washing soap.jpg.jpg'),
('Laundry Detergent', 'Powder laundry detergent', 20.00, 25, 'Groceries', 'images/Detergent.jpg.jpg'),
('Sponges', 'Cleaning sponges', 3.00, 50, 'Groceries', 'images/sponge.jpg.jpg'),
('Bleach', 'Household bleach', 5.50, 30, 'Groceries', 'images/Bleach.jpg.jpg'),
('Air Freshener', 'Room air freshener', 8.00, 25, 'Groceries', 'images/Air Freshener.jpg.jpg');

# Essentials - E-Commerce Platform

Essentials is a modern, full-featured e-commerce web application built with PHP, MySQL, and JavaScript. It provides a seamless shopping experience for customers and a comprehensive management system for staff members.

## Features

### Customer Features
- **User Authentication**: Secure login and registration system with password hashing
- **Product Browsing**: Browse products with category filtering
- **Shopping Cart**: Add products to cart with localStorage persistence
- **Checkout System**: Complete order processing with inventory management
- **Responsive Design**: Mobile-friendly interface that works on all devices

### Staff Features
- **Product Management**: Add new products with images, descriptions, and pricing
- **Inventory Control**: View and update product quantities
- **Product Listing**: View all products in a manageable interface
- **Access Control**: Secure staff-only pages with session-based authentication

## Technologies Used

- **Backend**: PHP (MySQLi)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache (XAMPP/WAMP)
- **Storage**: LocalStorage for cart management

## Prerequisites

Before you begin, ensure you have the following installed:
- [XAMPP](https://www.apachefriends.org/) or [WAMP](https://www.wamp.info/) (includes Apache, MySQL, PHP)
- A modern web browser (Chrome, Firefox, Edge, Safari)
- MySQL database server

## Installation & Setup

### 1. Clone or Download the Project
```bash
# If using git
git clone <repository-url>
cd Code-X-Sprint-3
```

Or download and extract the project to your web server directory:
- **XAMPP**: `C:\xampp\htdocs\Code-X-Sprint-3`
- **WAMP**: `C:\wamp64\www\Code-X-Sprint-3`

### 2. Database Setup

The database SQL file will be included with the project. To set up the database:

1. Start XAMPP/WAMP and ensure Apache and MySQL are running
2. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
3. Import the database SQL file (will be provided) or run the following SQL script:

   ```sql
   CREATE DATABASE IF NOT EXISTS Essentials;
   
   USE Essentials;
   
   -- USERS TABLE --
   CREATE TABLE Users (
       userID INT AUTO_INCREMENT PRIMARY KEY,
       firstName VARCHAR(70) NOT NULL,
       lastName VARCHAR(70) NOT NULL,
       email VARCHAR(100) UNIQUE NOT NULL,
       acc_password VARCHAR(100) NOT NULL,
       userType ENUM('customer', 'staff') NOT NULL DEFAULT 'customer'
   );
   
   -- PRODUCTS TABLE --
   CREATE TABLE Products (
       productID INT AUTO_INCREMENT PRIMARY KEY,
       productName VARCHAR(150) NOT NULL,
       info TEXT,
       price DECIMAL(10,2) NOT NULL,
       category VARCHAR(150) NOT NULL,
       availability BOOLEAN DEFAULT TRUE,
       image_url VARCHAR(300)
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
   INSERT INTO Products (productName, info, price, category, image_url) VALUES
   ('frytol cooking oil', '100% pure vegetable oil fortified with vitamin A', 20, 'Food', 'images/cooking_oil.jpg'),
   ('wheat drink', 'Flavored milk drink', 17, 'Beverages', 'images/wheat_drink.jpg'),
   ('voltic mineral water', 'Pure, zero calorie bottled water', 3, 'Beverages', 'images/water.jpg'),
   ('fluffy toilet roll', 'Affordable, ultrasoft and absorbent', 5, 'Toiletries', 'images/t_roll.jpg'),
   ('famous amos cookies', 'Premium chocolate chip cookies', 13, 'Snacks', 'images/cookies.jpg'),
   ('Niche chocolate', 'Flavorful and rich in milk', 20, 'Snacks', 'images/choco.jpeg'),
   ('Buldak', 'Hot chicken flavored noodles', 25, 'Groceries', 'images/buldak.jpg');
   
   -- INSERT STORE INVENTORY --
   INSERT INTO storeInventory (productID, stockQuantity, lowStockThreshold) VALUES
   (1, 50, 10),
   (2, 30, 5),
   (3, 100, 20),
   (4, 25, 5),
   (5, 75, 15),
   (6, 15, 3),
   (7, 40, 8);
   
   -- INSERT STAFF ACCOUNTS (Optional) --
   -- Note: Passwords should be hashed using password_hash() in PHP
   -- Example: INSERT INTO Users (firstName, lastName, email, acc_password, userType) 
   --          VALUES ('John', 'Doe', 'staff@example.com', '$2y$10$hashedpasswordhere', 'staff');
   ```

### 3. Configure Database Connection

Edit `connection.php` and update the database credentials if needed:
```php
$servername = "localhost";
$username = "root";        // Your MySQL username
$dbpassword = "";          // Your MySQL password
$dbname = "Essentials";    // Database name (capital E)
```



### 4. Access the Application

1. Start Apache and MySQL from XAMPP/WAMP control panel
2. Open your browser and navigate to:
   ```
   http://localhost/Code-X-Sprint-3/
   ```
   or
   ```
   http://localhost/Code-X-Sprint-3/homepage.html
   ```

## Project Structure

**Note**: The project structure would  reorganized into an MVC (Model-View-Controller) architecture. The current structure is temporary and subject to change.

### Current Structure (Temporary)
```
Code-X-Sprint-3/
│
├── index.php                 # Entry point (redirects to homepage)
├── homepage.html             # Landing page
├── homepagestyle.css         # Homepage styles
│
├── login.php                 # User login page
├── login_logic.php           # Login backend processing
├── login.js                  # Login frontend logic
│
├── signup.php                # User registration page
├── signup_logic.php          # Registration backend processing
├── signup.js                 # Registration frontend logic
│
├── products.php              # Product browsing page (customer view)
├── checkoutCart.php          # Shopping cart page
├── process_order.php         # Order processing endpoint
│
├── staff_dashboard.php       # Staff: Add new products
├── staff_product_page.php    # Staff: View/update products
├── update_quantity.php       # Staff: Update product quantity API
│
├── connection.php            # Database connection configuration
├── main.css                  # Main stylesheet for products/cart pages
├── authentication.css        # Stylesheet for login/signup pages
│
├── images/                   # Product images
├── homepageimages/           # Homepage showcase images
└── uploads/                 # Staff-uploaded product images
```

### Planned MVC Structure
The project will be reorganized into the following MVC structure:


## User Roles

### Customer
- Default user type for new registrations
- Can browse products, add to cart, and checkout
- Access: `products.php`, `checkoutCart.php` after signing up or login in

### Staff
- Can manage products (add, update quantities)
- Access: `staff_dashboard.php`, `staff_product_page.php`
- To create a staff account, you can either:
  
  **Option 1: Insert directly into the database**
  ```sql
  -- Note: Password must be hashed using PHP's password_hash() function
  INSERT INTO Users (firstName, lastName, email, acc_password, userType) 
  VALUES ('John', 'Doe', 'staff@example.com', '$2y$10$hashedpasswordhere', 'staff');
  ```
  
  **Option 2: Update an existing user**
  ```sql
  UPDATE Users SET userType = 'staff' WHERE email = 'staff@example.com';
  ```

## Usage Guide

### For Customers

1. **Registration/Login**
   - Visit the homepage and click "Get Started" or "Login"
   - Create an account or log in with existing credentials

2. **Shopping**
   - Browse products on the products page
   - Use category filters to find specific items
   - Click "Add to Cart" to add products

3. **Checkout**
   - View your cart by clicking the cart icon
   - Review items and quantities
   - Click "Confirm & Pay" to complete the order using paystack

### For Staff

1. **Login**
   - Log in with staff credentials
   - You'll be redirected to the staff dashboard

2. **Add Products**
   - Fill in product details (name, price, quantity, category, description)
   - Upload a product image
   - Click "Add Product"

3. **Manage Inventory**
   - View all products on the staff product page
   - Update quantities as needed
   - Products are automatically updated in the customer view

## Security Features

- Password hashing using PHP's `password_hash()` and `password_verify()`
- Session-based authentication
- SQL injection prevention using prepared statements
- Access control for staff-only pages
- Input validation and sanitization

## Design Features

- Modern, clean UI with consistent navigation
- Responsive design for mobile and desktop
- Smooth transitions and hover effects
- Consistent color scheme across all pages
- Professional typography and spacing

## Troubleshooting

### Database Connection Error
- Ensure MySQL is running in XAMPP/WAMP
- Verify database credentials in `connection.php`
- Check that the `Essentials` database exists (note the capital E)
- Ensure all tables are created correctly

### Images Not Displaying
- Verify image paths are correct
- Ensure image directory exists 
- Check file permissions on image files

### Session Issues
- Ensure `session_start()` is called before any output
- Check PHP session configuration in `php.ini`
- Clear browser cookies if experiencing login issues

### Cart Not Persisting
- Check browser localStorage support
- Ensure JavaScript is enabled
- Clear browser cache if cart appears empty

## Notes

- The shopping cart uses browser localStorage, so cart data is stored locally
- Product images should be uploaded through the staff dashboard
- Staff accounts can be inserted directly into the database or existing customer accounts can be updated to staff
- When inserting staff accounts, passwords must be hashed using PHP's `password_hash()` function
- The application uses MySQLi for database operations
- Inventory management is handled through the `storeInventory` table, separate from product availability
- The database includes pre-populated products and inventory data

## Future Enhancements

Potential improvements for future versions:
- Order history and tracking
- User profile management
- Product search functionality
- Email notifications
- Admin dashboard with analytics
- Product reviews and ratings

## License

This project is part of Code-X Sprint 3.

## Author

Developed as part of Code-X Sprint 3 project.

---

**Note**: This is a development project. For production use, ensure proper security measures, error handling, and database optimization are implemented.

# Essentials by CodeX - Convenience Store E-Commerce Platform

A modern web-based e-commerce platform for convenience store products, built with PHP, MySQL, and JavaScript. This application allows customers to browse and purchase groceries, beverages, snacks, and toiletries, while staff members can manage products and inventory.

##  Essentials e-commerce app link using Wix CMS
 live site: https://josephineallan369.wixsite.com/my-site-4

## Project Files
All supporting files can be found in the `Web Documentation` folder:
- Case Scenario
- Storyboard

## Features

### Customer Features
- **Browse Products**: View all available products with images and prices
- **Product Filtering**: Filter products by category (Beverages, Snacks, Toiletries, Groceries)
- **Shopping Cart**: Add products to cart with localStorage persistence
- **Product Images**: Automatic image generation using Unsplash API for products without uploaded images
- **Responsive Design**: Modern, mobile-friendly interface

### Staff Features
- **Add Products**: Upload new products with images, prices, and descriptions
- **Bulk Product Insertion**: Add 50+ convenience store products at once with automatic image generation
- **Manage Inventory**: Update product quantities in real-time
- **View All Products**: See all products with current stock levels

## Technology Stack

- **Backend**: PHP (MySQLi)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Image Service**: Unsplash Source API (for automatic product images)
- **Server**: XAMPP (Apache, MySQL, PHP)

## Project Structure

```
Code-X-Sprint-3/
├── authentication.css       # Login/Signup page styles
├── checkout.php             # Checkout page
├── checkoutCart.php         # Shopping cart page
├── connection.php           # Database connection configuration
├── homepage.html            # Landing page
├── homepagestyle.css        # Homepage styles
├── index.php                # Entry point (redirects to homepage)
├── login.php                # Customer/Staff login page
├── login_logic.php          # Login authentication logic
├── login.js                 # Login form handling
├── main.css                 # Main stylesheet
├── products.php             # Product listing page (customer view)
├── process_order.php        # Order processing
├── signup.php               # User registration page
├── signup_logic.php         # Registration logic
├── signup.js                # Signup form handling
├── staff_dashboard.php      # Staff dashboard (add products, bulk insert)
├── staff_product_page.php   # Staff product management page
├── update_quantity.php      # Update product quantity API
├── homepageimages/          # Homepage product category images
├── images/                  # Product images
└── uploads/                 # Uploaded product images (created automatically)
```

## Database Setup



### Creating the Database

```sql
CREATE TABLE Products (
    productID INT AUTO_INCREMENT PRIMARY KEY,
    productName VARCHAR(255) NOT NULL,
    info TEXT,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    image_url VARCHAR(500)
);






























```

## Installation & Setup

### Prerequisites
- XAMPP (or similar PHP/MySQL environment)
- Web browser

### Installation Steps

1. **Clone or download the project**
   ```bash
   # Place the project in your XAMPP htdocs folder
   C:\xampp\htdocs\Code-X-Sprint-3
   ```

2. **Configure Database**
   - Update `connection.php` if your MySQL credentials differ
   - Create the database and table as described above

3. **Start XAMPP Services**
   - Start Apache
   - Start MySQL

4. **Access the Application**
   - Homepage: `http://localhost/Code-X-Sprint-3/homepage.html`
   - Products Page: `http://localhost/Code-X-Sprint-3/products.php`
   - Staff Dashboard: `http://localhost/Code-X-Sprint-3/staff_dashboard.php`

## Usage Guide

### For Customers

1. **View Products**
   - Navigate to the products page
   - Browse all products or filter by category
   - Products display with images automatically generated if not uploaded

2. **Add to Cart**
   - Click "Add to Cart" on any product
   - View cart by clicking "Cart" in the navigation
   - Cart persists using browser localStorage

3. **Checkout**
   - Proceed to checkout from the cart page
   - Complete your order

### For Staff

1. **Add Single Product**
   - Log in as staff
   - Go to Staff Dashboard
   - Fill in product details
   - Upload an image (optional - will auto-generate if not provided)
   - Click "Upload Product"

2. **Bulk Add Products**
   - Log in as staff
   - Go to Staff Dashboard
   - Scroll to "Quick Add: Bulk Products" section
   - Click "Add 50+ Products Now"
   - This will add 50+ convenience store products with automatic images

3. **Manage Inventory**
   - Go to "Products View" from staff dashboard
   - Update product quantities as needed
   - Changes are saved immediately

## Product Categories

- **Beverages**: Soft drinks, juices, water, energy drinks
- **Snacks**: Chips, cookies, candy, nuts
- **Groceries**: Canned goods, pasta, rice, condiments, cleaning supplies
- **Toiletries**: Personal care items, soap, shampoo, toothpaste

## Image Handling

- **Manual Upload**: Staff can upload product images when adding products
- **Automatic Generation**: If no image is uploaded, the system automatically generates relevant product images using Unsplash Source API
- **Fallback**: If image loading fails, the system uses Unsplash with product name as search term

## Security Features

- Session-based authentication
- Prepared statements for SQL queries (prevents SQL injection)
- Input sanitization with `htmlspecialchars()`
- Access control for staff-only pages

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Edge
- Safari

## Notes

- The products page currently allows access without login (authentication check is commented out)
- Cart functionality uses localStorage, so it persists across sessions
- Product images are fetched from Unsplash API, which requires internet connection
- Uploaded images are stored in the `uploads/` directory (created automatically)

## Future Enhancements

- User authentication for products page
- Order history
- Product search functionality
- User reviews and ratings
- Payment gateway integration
- Admin dashboard with analytics

## Support

For issues or questions, please check:
- Database connection settings in `connection.php`
- XAMPP services are running
- File permissions for uploads directory

## License

This project is part of Code-X Sprint 3.

---

**Developed for Essentials Convenience Store**

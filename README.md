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
- **Responsive Design**: Modern, mobile-friendly interface

### Staff Features
- **Add Products**: Upload new products with images, prices, and descriptions
- **Manage Inventory**: Update product quantities in real-time
- **View All Products**: See all products with current stock levels

## Technology Stack

- **Backend**: PHP (MySQLi)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Image Service**: Unsplash Source API (for automatic product images)
- **Server**: XAMPP (Apache, MySQL, PHP)



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
   - Click "Add 50+ Products Now."
   - This will add 50+ convenience store products with automatic images

3. **Manage Inventory**
   - Go to "Products View" from the staff dashboard
   - Update product quantities as needed
   - Changes are saved immediately

## Product Categories
- **Beverages**: Soft drinks, juices, water, energy drinks
- **Snacks**: Chips, cookies, candy, nuts
- **Groceries**: Canned goods, pasta, rice, condiments, cleaning supplies
- **Toiletries**: Personal care items, soap, shampoo, toothpaste



## Security Features

- Session-based authentication
- Prepared statements for SQL queries (prevents SQL injection)
- Input sanitization with `htmlspecialchars().`
- Access control for staff-only pages

## Browser Compatibility
- Chrome (recommended)
- Firefox
- Edge
- Safari


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

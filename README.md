# Essentials by CodeX

A web-based e-commerce platform for convenience store products, built with PHP, MySQL, and JavaScript. This application allows customers to browse and purchase products
while staff members can manage inventory.

##  Essentials e-commerce app link using Wix CMS
Live site: https://josephineallan369.wixsite.com/my-site-4

## Project Files
All supporting files can be found in the `Web Documentation` folder:
- Case Scenario
- Storyboard

## Features

### Customer Features
- **Browse Products**: View all available products with images and prices
- **Product Filtering**: Filter products by category (Beverages, Snacks, Toiletries, Groceries)
- **Shopping Cart**: Add products to the cart 
- **Responsive Design**: Modern, mobile-friendly interface

### Staff Features
- **Add Products**: Upload new products with images, prices, and descriptions
- **Manage Inventory**: Update product quantities in real-time
- **View All Products**: See all products with current stock levels

## Technology Stack

- **Backend**: PHP (MySQLi)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript


## Usage Guide

### For Customers

1. **View Products**
   - Navigate to the products page
   - Browse all products or filter by category
   - Products display with images automatically generated if not uploaded

2. **Add to Cart**
   - Click "Add to Cart" on any product
   - View cart by clicking "Cart" in the navigation
   - Remove from cart (optional)


3. **Checkout**
   - Proceed to checkout and  from the cart page
   - Complete your order
   

### For Staff

1. **Add Single Product**
   - Log in as staff
   - Go to Staff Dashboard
   - Fill in product details
   - Click "Upload Product"


2. **Manage Inventory**
   - Go to "Products View" from the staff dashboard
   - Update product quantities as needed
   - Changes are saved immediately

## Security Features

- Session-based authentication
- Prepared statements for SQL queries (prevents SQL injection)
- Access control for staff-only pages

## Future Enhancements
- Order history
- Product search functionality
- User reviews and ratings
- Payment gateway integration
- Admin dashboard with analytics
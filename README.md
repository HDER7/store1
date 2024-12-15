# Square 1 Academy Store API

## Project Overview

This Laravel-based backend provides a comprehensive e-commerce API solution with user authentication, product management, shopping cart functionality, and order processing.

## Key Features

- User Registration and Authentication
- Product Catalog Management
- Shopping Cart Functionality
- Order Creation and Tracking
- Secure API Endpoints with Sanctum Authentication

## Technology Stack

- Laravel
- Laravel Sanctum (Authentication)
- MySQL Database

## Prerequisites

- PHP 8.0+
- Composer
- MySQL
- Laravel 8 or higher

## Installation Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/ecommerce-backend.git
   cd ecommerce-backend
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Create environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Configure database in `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

## API Endpoints

### User Endpoints
- `POST /register`: Register a new user
- `POST /login`: Authenticate user and receive access token
- `GET /profile`: Retrieve authenticated user information
- `POST /logout`: Log out the authenticated user

### Product Endpoints
- `GET /products`: List all available products with variants
- `GET /products/{ProductID}`: Get details of a specific product

### Shopping Cart Endpoints
- `GET /cart`: View the authenticated user's cart contents
- `POST /cart/add`: Add a product/variant to the cart
- `PUT /cart/update/{CartItemID}`: Update product quantity in cart
- `DELETE /cart/remove/{CartItemID}`: Remove a product from cart

### Order Endpoints
- `POST /orders/create`: Create an order from the user's cart
- `GET /orders`: List all orders for the authenticated user
- `GET /orders/{OrderID}`: Get details of a specific order

## Authentication

The API uses Laravel Sanctum for authentication. All endpoints related to cart, orders, and user profile require a valid access token.

## Key Validations

- Orders cannot be created with an empty cart
- Users can only access and modify their own data and resources

## Error Handling

The API provides clear error responses for various scenarios, including:
- Authentication failures
- Validation errors
- Resource not found
- Insufficient permissions

## Security Notes

- Passwords are hashed before storage
- API routes are protected with Sanctum authentication
- User-specific data access is strictly controlled
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

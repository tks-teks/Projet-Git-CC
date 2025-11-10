# Pharmacy Dashboard

## Overview
The Pharmacy Dashboard is a web application designed to help pharmacies manage their stock efficiently. It provides functionalities for viewing, adding, modifying, and handling stock shortages of medications.

## Project Structure
```
pharmacy-dashboard
├── public
│   ├── index.php              # Entry point for the application
│   ├── dashboard.php          # Displays the pharmacy dashboard
│   ├── login.php              # User login interface
│   ├── logout.php             # Handles user logout
│   └── assets
│       ├── css
│       │   └── app.css        # CSS styles for the application
│       └── js
│           └── app.js         # JavaScript code for client-side functionality
├── src
│   ├── Controllers
│   │   ├── AuthController.php  # Manages user authentication
│   │   ├── DashboardController.php # Handles dashboard logic
│   │   └── StockController.php  # Manages stock operations
│   ├── Models
│   │   ├── Medicament.php      # Represents a medication entity
│   │   ├── Stock.php           # Represents stock information
│   │   └── Pharmacie.php       # Represents a pharmacy entity
│   ├── Views
│   │   ├── layout.php          # Main layout template
│   │   ├── dashboard.php       # HTML structure for the dashboard view
│   │   └── auth
│   │       └── login.php       # HTML structure for the login view
│   ├── Services
│   │   └── NotificationService.php # Handles notifications and alerts
│   └── Helpers
│       └── csrf.php            # CSRF token generation and validation
├── config
│   ├── config.php              # Configuration settings
│   └── database.php            # Database connection settings
├── inc
│   └── db.php                  # Database connection file
├── migrations
│   └── 001_create_tables.sql   # SQL for creating database tables
├── scripts
│   └── seed.sql                # SQL for seeding the database
├── tests
│   ├── phpunit.xml             # PHPUnit configuration file
│   └── StockTest.php           # Unit tests for the Stock model
├── .env.example                 # Example environment configuration
├── composer.json                # Composer configuration file
└── README.md                   # Project documentation
```

## Installation
1. Clone the repository:
   ```
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```
   cd pharmacy-dashboard
   ```
3. Install dependencies using Composer:
   ```
   composer install
   ```
4. Set up your environment variables by copying `.env.example` to `.env` and updating the values as needed.

## Usage
- Access the application by navigating to `http://localhost/pharmacy-dashboard/public/index.php` in your web browser.
- Use the login page to authenticate and access the dashboard.
- Manage stock by adding, updating, and viewing medications.

## Contributing
Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License
This project is licensed under the MIT License. See the LICENSE file for details.
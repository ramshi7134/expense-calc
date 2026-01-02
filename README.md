<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Expense Monitor

Expense Monitor is a modern, intuitive web application designed to help you effortlessly track your monthly expenses, manage budgets, and gain insights into your spending habits through a clean and visual dashboard.

![Expense Monitor Dashboard](https://i.imgur.com/your-screenshot-url.png) <!-- You can replace this URL with a direct link to your screenshot -->

## Key Features

-   **Interactive Dashboard**: Get a quick overview of your total expenses and remaining budget for the current month.
-   **Category Management**: Create, edit, and delete expense categories to organize your spending.
-   **Budgeting**: Set monthly spending limits for different categories to stay on track with your financial goals.
-   **Expense Tracking**: Easily log your daily expenses and assign them to categories.
-   **Visual Reports**:
    -   A donut chart showing the percentage breakdown of your expenses by category.
    -   A bar chart comparing your budgeted amounts versus actual spending for each category.
-   **Monthly Filtering**: Navigate through different months to review past spending data.
-   **Secure Authentication**: User registration and login system to keep your financial data private.
-   **Responsive Design**: A clean and modern UI that works seamlessly on both desktop and mobile devices.

## Technology Stack

-   **Backend**: Laravel, PHP
-   **Frontend**: Bootstrap 5, Sass, Vite.js
-   **Charting**: ApexCharts
-   **Database**: SQLite (default), MySQL compatible

## Installation

Follow these steps to set up the project locally.

### 1. Clone the Repository

```bash
git clone https://github.com/ramshi7134/expense-calc.git
cd expense-calc
```

### 2. Install Dependencies

Install both Composer (PHP) and NPM (JavaScript) dependencies.

```bash
composer install
npm install
```

### 3. Set Up Environment File

Copy the example environment file and generate your application key.

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database

This project uses SQLite by default, which requires no extra configuration. Simply create the database file:

```bash
touch database/database.sqlite
```

If you prefer to use MySQL, update your `.env` file with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expense_monitor
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations

Apply the database schema to your database.

```bash
php artisan migrate
```

### 6. Run the Application

You need to run two commands concurrently in separate terminal tabs: one for the PHP server and one for the Vite development server.

**Terminal 1: Start the Laravel Server**

```bash
php artisan serve
```

**Terminal 2: Start the Vite Server**

```bash
npm run dev
```

You can now access the application at [http://localhost:8000](http://localhost:8000).

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

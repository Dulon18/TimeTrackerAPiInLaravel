<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<h1>Time Tracker API</h1>

# Set up or Clone process
## 1. Clone the repository

1. git clone https://github.com/Dulon18/TimeTrackerAPiInLaravel.git
2. cd your-project

## 2. Install dependencies
composer install

## 3. Copy .env file and generate app key

cp .env.example .env
php artisan key:generate

## 4. Configure your .env file
## (Set your DB credentials and other environment settings)

## 5. Run database migrations (and optionally seeders)
php artisan migrate
## 6 Run Seeder
php artisan db:seed

## 7. Start the development server
php artisan serve

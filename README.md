<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Book SMS - Admin Panel (Filament)

Admin panel built with Filament for managing products, categories, suppliers, customers, purchases, sales, and expenses. Includes strong authentication (email verification) and a dashboard with charts.

### Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL/SQLite

### Setup

```bash
cp .env.example .env
php artisan key:generate
composer install
php artisan migrate --seed
php artisan make:filament-user
npm install && npm run build
```

Visit `/admin` to log in.

### Strong Auth

- Email verification is enabled. Users must verify before accessing the panel.
- Passwords are hashed and validated in the `UserResource` create/edit forms.

### Dashboard UI with Charts

The dashboard includes three line charts for the last 7 days:

- Sales: `App\\Filament\\Widgets\\SalesOverviewChart`
- Purchases: `App\\Filament\\Widgets\\PurchasesOverviewChart`
- Expenses: `App\\Filament\\Widgets\\ExpensesOverviewChart`

These are registered in `App\\Providers\\Filament\\AdminPanelProvider`.

### Filament Resources

- Users: `App\\Filament\\Resources\\UserResource`
- Products: `App\\Filament\\Resources\\ProductResource`
- Categories: `App\\Filament\\Resources\\CategoryResource`
- Customers: `App\\Filament\\Resources\\CustomerResource`
- Suppliers: `App\\Filament\\Resources\\SupplierResource`

Each resource provides CRUD with searchable/sortable tables and validated forms.

### Project Structure

```text
app/
  Filament/
    Resources/
      CategoryResource.php
      CustomerResource.php
      ProductResource.php
      SupplierResource.php
      UserResource.php
    Widgets/
      ExpensesOverviewChart.php
      PurchasesOverviewChart.php
      SalesOverviewChart.php
  Http/
    Controllers/
  Models/
    Category.php
    Customer.php
    Expense.php
    Product.php
    Purchase.php
    Sale.php
    Supplier.php
    User.php
    Warehouse.php
  Providers/
    Filament/
      AdminPanelProvider.php

database/
  migrations/
  seeders/

routes/
  web.php
```

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


#Factories
AuditLog
categories
customers
expenses
order_items
orders
product_variants
products
purchase_items
purchases
roles
sale_items
sales
stock_movements
supplier_products
suppliers
warehouses
users

#migrations
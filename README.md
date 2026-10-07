# Scrap Management System

An online marketplace where people can buy recycled scrap products, place orders and pay online or on delivery, with an admin panel to manage everything. Diploma project, built with **PHP + MySQL**.

The site is responsive and works like an app on phones (bottom tab bar, slide-up menus, installable to the home screen).

## Features

**Customers**
- Browse scrap products by category, search, product details with photo gallery
- Cart with live quantity update, checkout (online via PayPal sandbox, or pay on delivery)
- Register / login / forgot password (security question)
- My account: dashboard, order tracking, cancel unpaid orders, invoices, profile with photo
- Feedback form

**Admin**
- Dashboard with order, revenue, user and product stats
- Manage orders (status, payment, delivery), printable invoices
- Manage categories, products and product photos (show/hide on the website)
- Manage users (block / activate, delete) and customer feedback
- Admin profile

## Tech

PHP 8, MySQL / MariaDB, plain HTML/CSS/JS (no framework). Fonts: Plus Jakarta Sans; icons: Font Awesome; alerts: SweetAlert.

## Run it locally (XAMPP)

1. Install [XAMPP](https://www.apachefriends.org) and start **MySQL**.
2. Create a database named `nsp_scrap` and import `database/nsp_scrap_demo.sql` (phpMyAdmin > Import).
3. Start PHP's server in the project folder:
   ```
   C:\xampp\php\php.exe -S localhost:8000
   ```
4. Open http://localhost:8000

The default database login (`root`, no password, database `nsp_scrap`) is in `admin/db.php`. To use other details, copy `admin/config.example.php` to `admin/config.php` and edit it.

### Demo accounts

| Role | Email | Password |
|---|---|---|
| Customer | demo@example.com | Demo@1234 |
| Customer | user@example.com | User@1234 |
| Admin (`/admin/`) | admin@example.com | ChangeMe@123 |

**Change the admin password** (Admin > Profile) as soon as the site is online.

## Put it online

See [DEPLOY.md](DEPLOY.md) for a step-by-step guide to free hosting on InfinityFree.

## Project structure

```
index.php, scrap.php, productDetails.php, cart.php, ...   public website
admin/                 admin panel + customer "My account" pages
admin/ui/              shared layouts for admin and account pages
ui/                    shared header / footer / <head> for the website
assets/                sms.css, admin.css, sms.js, admin.js (the new design)
payment/               PayPal sandbox checkout
database/              nsp_scrap_demo.sql (demo data)
images/                logo (images/make_logo.php redraws it)
```

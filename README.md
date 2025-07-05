<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Tutorial: laravel_ecommerce

This project is a **Laravel e-commerce website** where users can *browse products* and categories, add items to a *shopping cart*, and complete purchases through a *checkout* process. It includes a *user authentication system* for managing accounts and viewing *order history*. An *admin panel*, protected by *middleware*, allows administrators to manage products, categories, and orders.


## Visual Overview

```mermaid
flowchart TD
    A0["Eloquent Models
"]
    A1["Routing
"]
    A2["Controllers
"]
    A3["Blade Views
"]
    A4["Authentication System
"]
    A5["Admin Middleware
"]
    A6["Cart Management (using Facade)
"]
    A7["E-commerce Models (Product, Category, Order, OrderItem)
"]
    A1 -- "Directs requests to" --> A2
    A2 -- "Interacts with" --> A7
    A7 -- "Extends base" --> A0
    A4 -- "Uses User model" --> A0
    A2 -- "Provides data for" --> A3
    A3 -- "Displays data via" --> A6
    A2 -- "Modifies data via" --> A6
    A4 -- "Authenticates users for" --> A2
    A4 -- "Provides user role to" --> A5
    A5 -- "Protects" --> A1
```

## Chapters

1. [Routing
](01_routing_.md)
2. [Controllers
](02_controllers_.md)
3. [Blade Views
](03_blade_views_.md)
4. [Eloquent Models
](04_eloquent_models_.md)
5. [E-commerce Models (Product, Category, Order, OrderItem)
](05_e_commerce_models__product__category__order__orderitem__.md)
6. [Authentication System
](06_authentication_system_.md)
7. [Cart Management (using Facade)
](07_cart_management__using_facade__.md)
8. [Admin Middleware
](08_admin_middleware_.md)

---
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

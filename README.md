<div align="center">
  <img src="public/assets/images/hero-banner.png" width="100%" alt="Saloon Management System Banner">
  
  # ✨ Saloon Management & Live Queue System
  
  [![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue.svg?style=for-the-badge&logo=php)](https://php.net)
  [![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)
  
  **Transforming the Salon Experience with Live Queues and Premium Insights.**
</div>

---

## 🌟 Executive Summary

A high-end, production-ready **Saloon Management System** built with **Laravel 12**. This platform features a unique **Live Queue Management** system and a **Stripe-powered Subscription** model, providing a seamless experience for Super Admins, Saloon Owners, and Customers.

---

## 🚀 Core Pillar Features

### ⏱️ Live Queue Management
> *Say goodbye to waiting rooms.*
- **Dynamic Token Generation**: Automated numbering for walk-ins and bookings.
- **Real-time Status tracking**: Customers see "People ahead" and "Estimated Wait Time" in real-time.
- **Admin Command Center**: Single-click "Start serving," "Done," or "Cancel" controls.
- **Smart History**: Auto-archiving of completed sessions for performance analytics.

### 💳 Stripe & QR Revenue Engine
- **Multi-Tier Subscriptions**: Silver, Gold, and Platinum tiers to drive platform revenue.
- **Instant Recharge**: Saloon admins can self-recharge via Stripe or secure QR payments.
- **Visibility Logic**: Subscription-based search prioritization—Platinum salons always shine first.
- **Automatic Enforcement**: Expired accounts are gracefully marked as "Temporarily Closed" to maintain platform integrity.

### 🏢 Triple-Panel Infrastructure
- **Super Admin**: Governance at scale. Saloon verification, system-wide analytics, and customer data isolation.
- **Saloon Admin**: Complete business suite—Staff, Services, Coupons, and daily revenue stats.
- **Customer Panel**: Premium discovery experience with location-based (State/City) filtering.

---

## 🎨 Design Excellence
This project isn't just functional; it's **Editorial**:
- **Rich Aesthetics**: Vibrant gradients, glassmorphism, and modern typography (Inter).
- **Micro-animations**: Smooth transitions that feel fluid and premium.
- **Responsive by Default**: A flawless experience on mobile, tablet, and desktop.

---

## �️ Performance Stack
- **Backend**: Laravel 12.x (Latest)
- **Frontend**: Blade, Modern CSS3, Tailwind-optimized components
- **Payments**: Stripe Checkout & UPI QR Integration
- **Database**: MySQL (Optimized with proper indexing)
- **Workflow**: Vite, Laravel Breeze (Role-customized)

---

## 🏁 Quick Start Guide

### 1. Zero-Click Setup
We've included automated scripts to get you running in seconds.
```bash
# Windows
./setup.bat

# Linux/Mac
chmod +x setup.sh && ./setup.sh
```

### 2. Manual Installation
```bash
git clone <repository-url>
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
```

---

## 🔑 Demo Accounts

| Role | Email | Password |
| :--- | :--- | :--- |
| **👑 Super Admin** | `admin@saloon.com` | `password` |
| **🏢 Saloon Admin** | `saloon@saloon.com` | `password` |
| **👤 Customer** | `user@saloon.com` | `password` |

---

## � Architecture at a Glance
```text
├── app/Http/Controllers/
│   ├── SuperAdmin/      # Platform Governance
│   ├── SaloonAdmin/     # Business Operations
│   └── User/            # Customer Discovery
├── database/            # 17+ Optimized Tables
├── resources/views/     # Multi-panel Blade UI
└── routes/web.php       # Role-secure Routing
```

---

<div align="center">
  <p><i>Version 1.2.0 | January 2026</i></p>
  
  [Documentation](DOCUMENTATION.md) • [Project Summary](PROJECT_SUMMARY.md) • [Quickstart](QUICKSTART.md)
</div>
# Maintenance Requests API

A robust RESTful API for managing property maintenance requests with role-based access control, built with Laravel 12 and Laravel Sanctum for authentication.

## 🚀 Quick Start

```bash
# Clone the repository
git clone <repository-url>
cd maintenance-requests-api

# Copy environment file
cp .env.example .env

# Install dependencies
composer install

# Generate application key
php artisan key:generate

php artisan migrate

# Seed users (optional)
php artisan db:seed --class=UsersSeeder

# Run tests
php artisan test

```

## 📋 Requirements

- PHP 8.2+
- Composer
- MySQL

## 🏗️ Architecture Overview

### Design Pattern: Action-Based Architecture

This project uses an **Action-based architecture**:

- **Controllers**: Thin, only handle HTTP concerns (validation, responses)
- **Actions**: Single-responsibility classes that encapsulate business logic
- **Events & Listeners**: Decouple side effects from core business logic
- **Policies**: Centralized authorization logic
- **Form Requests**: Dedicated validation classes

### Key Architectural Decisions

#### 1. **Laravel Sanctum for Authentication**
- Lightweight token-based authentication
- No OAuth complexity for internal API
- SPA-friendly with cookie-based tokens
- Easy integration with mobile apps

#### 2. **Event-Driven Architecture for Side Effects**
- `MaintenanceRequestCreated` → Logs activity + sends notification
- `MaintenanceRequestAssigned` → Logs activity + notifies technician
- `MaintenanceRequestStatusChanged` → Logs activity + notifies creator

#### 3. **Role-Based Access Control (RBAC)**
- Roles: `admin`, `user`, `technician`
- Middleware: `role:admin` guards sensitive endpoints
- Policies: Fine-grained authorization per resource

#### 4. **Queued Notifications**
- `SendMaintenanceNotificationJob` processes notifications asynchronously
- Prevents blocking HTTP requests
- Improves response times

#### 5. **Activity Logging**
- `MaintenanceActivity` model tracks all changes


#### 6. **Enum-Based Status & Priority**
- Type-safe status transitions
- Validation at type level

```php
MaintenanceRequestStatus: pending, in_progress, completed, cancelled
MaintenanceRequestPriority: low, medium, high, urgent
```
<div align="center">

# PesanWisata API

A Laravel-based RESTful API for managing tourism destinations, user accounts, and tour bookings with role-based access control and session-based authentication using Laravel Sanctum.

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Sanctum](https://img.shields.io/badge/Sanctum-Auth-F05340?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/docs/sanctum)
[![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

</div>

---

## 📌 Project Overview

PesanWisata API serves as the backend for the PesanWisata tourism booking application. It provides API endpoints for authentication, destination management, and booking operations for Admin and User (Customer) roles.

The API connects with the companion frontend client:

🌐 Frontend Repository: [pesanwisata](https://github.com/andraadev/pesanwisata)

---

## ✨ Key Features

- **Session-Based Authentication (Laravel Sanctum)**
    - Secure user registration and login endpoints.
    - SPA authentication using session cookies.
    - Protected API requests using authenticated sessions.
    - Automatic password hashing.

- **Destination Management**
    - Complete CRUD operations for tourist destinations.
    - Automatic URL-friendly slug generation from destination names.
    - Image upload handling with storage management and cleanup on deletion/update.
    - Detailed destination attributes: title, slug, location, description, and photo.

- **Tour Booking System**
    - Users can create bookings and view their booking history.
    - Admins can view all booking records.
    - Bookings are associated with users, destinations, booking dates, and status.

- **Role-Based Access Control (RBAC)**
    - Separate roles: **Admin** and **User**.
    - Protected endpoints use authentication and role-based middleware to enforce access control.

- **User Management**
    - Admin CRUD access for system users and role administration.
    - Unique email validation and secure credential management.

- **Standardized API Response**
    - Consistent JSON response format (`APIResource`) across all endpoints:
        ```json
        {
          "success": true,
          "message": "Operation message",
          "data": { ... }
        }
        ```

---

## 👥 User Roles

| Role                | Responsibilities                                                                                                                 |
| :------------------ | :------------------------------------------------------------------------------------------------------------------------------- |
| **Admin**           | Manage tourism destinations (CRUD, image uploads), manage users and roles, review tourist booking records.                       |
| **User (Customer)** | Register and login, browse destination catalog and details by slug, create tour bookings, and view personal booking information. |

---

## 🛣️ API Endpoints Reference

### 1. Authentication

| Method | Endpoint        | Description                    | Auth Required |
| :----- | :-------------- | :----------------------------- | :------------ |
| `POST` | `/api/register` | Register a new user account    | No            |
| `POST` | `/api/login`    | Authenticate user              | No            |
| `POST` | `/api/logout`   | Log out the authenticated user | Yes           |

### 2. Destinations

| Method     | Endpoint                                | Description                                               | Auth Required   |
| :--------- | :-------------------------------------- | :-------------------------------------------------------- | :-------------- |
| `GET`      | `/api/destinations`                     | List all available destinations                           | No              |
| `GET`      | `/api/destinations/{destination}`       | Get destination details by slug                           | No              |
| `POST`     | `/api/admin/destinations`               | Create a new destination (multipart/form-data with image) | Yes (`Sanctum`) |
| `GET`      | `/api/admin/destinations/{destination}` | Get destination detail by ID                              | Yes (`Sanctum`) |
| `PUT/POST` | `/api/admin/destinations/{destination}` | Update destination information or photo                   | Yes (`Sanctum`) |
| `DELETE`   | `/api/admin/destinations/{destination}` | Delete a destination and its image                        | Yes (`Sanctum`) |

### 3. Bookings

| Method | Endpoint             | Description                                             | Auth Required   |
| :----- | :------------------- | :------------------------------------------------------ | :-------------- |
| `GET`  | `/api/admin/booking` | List all booking records with user and destination info | Yes (`Sanctum`) |
| `POST` | `/api/user/booking`  | Create a new tour booking                               | Yes (`Sanctum`) |

### 4. User Management

| Method   | Endpoint                | Description                      | Auth Required   |
| :------- | :---------------------- | :------------------------------- | :-------------- |
| `GET`    | `/api/admin/users`      | List all registered users        | Yes (`Sanctum`) |
| `POST`   | `/api/admin/users`      | Create a new user account        | Yes (`Sanctum`) |
| `GET`    | `/api/admin/users/{id}` | Get user detail by ID            | Yes (`Sanctum`) |
| `PUT`    | `/api/admin/users/{id}` | Update user information and role | Yes (`Sanctum`) |
| `DELETE` | `/api/admin/users/{id}` | Delete a user account            | Yes (`Sanctum`) |

---

## 🛠️ Tech Stack

- **PHP 8.3+**
- **Laravel**
- **Laravel Sanctum**
- **MySQL / SQLite**

---

## 📦 Packages

| Package                                             | Purpose                        | Status  |
| :-------------------------------------------------- | :----------------------------- | :------ |
| [Laravel Sanctum](https://laravel.com/docs/sanctum) | Token-based API authentication | Used ✅ |
| [Laravel Framework](https://laravel.com/)           | Core application framework     | Used ✅ |

---

## ⚡ Quick Install

### Prerequisites

- PHP 8.3 or higher
- Composer
- MySQL
- GD / Fileinfo PHP Extensions (for image uploads)

### Installation Steps

1. **Clone the repository**

    ```bash
    git clone https://github.com/andraadev/pesanwisata-api.git
    cd pesanwisata-api
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Configure the environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Configure your database credentials in `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pesanwisata
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    _(Alternatively, use `DB_CONNECTION=sqlite`)_

4. **Run migrations and seeders**

    ```bash
    php artisan migrate --seed
    ```

5. **Create the public storage link**

    ```bash
    php artisan storage:link
    ```

6. **Start the development server**

    ```bash
    php artisan serve
    ```

7. The API will be accessible at:

    `http://127.0.0.1:8000/api`

---

## ⚠️ Disclaimer

This software is provided "as is", without warranty of any kind, express or implied.

The user assumes all responsibility and risk for the use of the software. No official support or maintenance is provided.

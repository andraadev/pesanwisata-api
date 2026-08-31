<div align="center">

# PesanWisata API

A Laravel-based RESTful API service for tourism destination management and tour booking system with Sanctum token authentication, destination catalog with image handling, role-based workflows, and booking management.

[![Laravel](https://img.shields.io/badge/Laravel-11%2B-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Sanctum](https://img.shields.io/badge/Sanctum-Auth-F05340?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/docs/sanctum)
[![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

</div>

---

## 📌 Project Overview

**PesanWisata API** is a Laravel-powered RESTful API backend designed to manage tourist destinations, user accounts, and travel/tour booking transactions.

The system provides role-based workflows for **Admin** and **User (Customer)**, handling authentication, destination catalog management with media upload, slug-based destination routing, and tour booking operations.

This backend serves as the core API service and connects directly with the companion frontend client:

- 🌐 **Frontend Repository**: [pesanWisata-FE](https://github.com/andraadev/pesanWisata-FE)

---

## ✨ Key Features

- **Token-Based Authentication (Laravel Sanctum)**
    - Secure user registration and login endpoints.
    - Bearer token issuance and revocation for authenticated API requests.
    - Password hashing using `bcrypt`.

- **Destination Management**
    - Complete CRUD operations for tourist destinations.
    - Automatic URL-friendly slug generation from destination names.
    - Image upload handling with storage management and cleanup on deletion/update.
    - Detailed destination attributes: title, slug, location, description, and photo.

- **Tour Booking System**
    - Create, view, update, and cancel destination bookings.
    - Association between users, destinations, booking dates, and status.
    - Joined relational queries for seamless data retrieval.

- **Role-Based Access Control (RBAC)**
    - Separate roles: **Admin** and **User**.
    - Secured admin endpoints protected by `auth:sanctum` middleware.

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
| **Admin**           | Manage tourism destinations (CRUD, image uploads), manage users and roles, review and update all tourist booking records.        |
| **User (Customer)** | Register and login, browse destination catalog and details by slug, create tour bookings, and view personal booking information. |

---

## 🛣️ API Endpoints Reference

### 1. Authentication

| Method | Endpoint        | Description                               | Auth Required |
| :----- | :-------------- | :---------------------------------------- | :------------ |
| `POST` | `/api/register` | Register a new user account               | No            |
| `POST` | `/api/login`    | Authenticate user and obtain Bearer token | No            |

### 2. Destinations

| Method     | Endpoint                       | Description                                               | Auth Required   |
| :--------- | :----------------------------- | :-------------------------------------------------------- | :-------------- |
| `GET`      | `/api/destinations`            | List all available destinations                           | No              |
| `GET`      | `/api/destination/{slug}`      | Get destination details by slug                           | No              |
| `POST`     | `/api/admin/destinations`      | Create a new destination (multipart/form-data with image) | Yes (`Sanctum`) |
| `GET`      | `/api/admin/destinations/{id}` | Get destination detail by ID                              | Yes (`Sanctum`) |
| `PUT/POST` | `/api/admin/destinations/{id}` | Update destination information or photo                   | Yes (`Sanctum`) |
| `DELETE`   | `/api/admin/destinations/{id}` | Delete a destination and its image                        | Yes (`Sanctum`) |

### 3. Bookings

| Method   | Endpoint               | Description                                             | Auth Required   |
| :------- | :--------------------- | :------------------------------------------------------ | :-------------- |
| `GET`    | `/api/booking`         | List all booking records with user and destination info | No / Optional   |
| `POST`   | `/api/booking`         | Create a new tour booking                               | No / Optional   |
| `GET`    | `/api/booking/{id}`    | Get booking details                                     | No / Optional   |
| `PUT`    | `/api/booking/{id}`    | Update booking data / status                            | No / Optional   |
| `DELETE` | `/api/booking/{id}`    | Remove a booking record                                 | No / Optional   |
| `*`      | `/api/admin/booking/*` | Administrative booking resource management              | Yes (`Sanctum`) |

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
- **Laravel Framework 11+**
- **Laravel Sanctum** (API Token Authentication)
- **MySQL / SQLite**
- **RESTful API Architecture**

---

## 📦 Packages

| Package                                             | Purpose                        | Status  |
| :-------------------------------------------------- | :----------------------------- | :------ |
| [Laravel Sanctum](https://laravel.com/docs/sanctum) | Token-based API authentication | Used ✅ |
| [Laravel Framework](https://laravel.com/)           | Core application framework     | Used ✅ |
| [Laravel Tinker](https://github.com/laravel/tinker) | Interactive CLI runtime shell  | Used ✅ |

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

## 🔐 Authentication & Default Credentials

> Default seeded accounts are intended for development and testing purposes only.

- **Test User**
    - Email: `test@example.com`
    - Password: `password`

### Using the API Token

When making requests to protected routes, include the Bearer token in the `Authorization` header:

```http
Authorization: Bearer <your_access_token>
Accept: application/json
```

---

## 🔗 Related Repository

- **Frontend Client**: [pesanWisata-FE](https://github.com/andraadev/pesanWisata-FE) — The client-side user interface connecting to this API backend.

---

## 📌 Project Status

> **Maintained, but development is limited**

This project was developed as a backend API service for a tourism and travel destination booking platform.

The application has reached a functional state for its intended scope and is maintained for critical bug fixes, security improvements, and necessary adjustments. Future feature development is not guaranteed and may depend on project needs.

The project is primarily provided for educational, reference, and portfolio purposes and is **not recommended for production use without further security review, testing, and environment-specific configuration**.

---

## ⚠️ Disclaimer

This software is provided "as is", without warranty of any kind, express or implied.

The user assumes all responsibility and risk for the use of the software. No official support or maintenance is provided.

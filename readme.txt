# Multi-Role ERP Platform

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![React](https://img.shields.io/badge/React-20232A?style=for-the-badge&logo=react&logoColor=61DAFB)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

A full-stack ERP (Enterprise Resource Planning) platform for educational institutions, featuring role-based access for admins and students, secure authentication, and profile management.

---

## Table of Contents

- [Overview](#overview)
- [Folder Structure](#folder-structure)
- [Features](#features)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Backend Setup](#backend-setup-phpserver)
  - [Frontend Setup](#frontend-setup-frontend)
- [API Overview](#api-overview)
- [Security](#security)
- [Database Schema](#database-schema)
- [Example Usage](#example-usage)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

---

## Overview

This project is a full-stack ERP platform designed for educational institutions. It supports multiple user roles (admin, student) and provides secure authentication, student admissions, and profile management. The backend is built with PHP (using PDO for database access), and the frontend is a modern JavaScript application (React or Vite-based).

---
## Folder Structure

.
├── frontend/
│   ├── build/              # Production build output
│   ├── public/             # Static public assets
│   ├── src/                # Frontend source code
│   ├── package.json        # Frontend dependencies and scripts
│   └── server.js           # (Optional) Node.js server for frontend dev/proxy
│
└── phpserver/
├── api/                # PHP API endpoints (auth, student, etc.)
│   ├── auth/
│   │   ├── login.php
│   │   ├── logout.php
│   │   ├── register.php
│   │   └── me.php
│   └── student/
│       ├── admissions.php
│       └── profile.php
├── src/                # PHP source (Auth, Config, Database)
│   ├── Auth/
│   │   └── AuthHelper.php
│   ├── Config/
│   │   ├── config.php
│   │   └── .env.local.php
│   └── Database/
│       └── DBConnection.php
├── schemas/            # (Optional) Database schema files
└── API_DOC.md          # Backend API documentation

---

## Features

- **User Registration and Login**: Secure authentication for students and admins.
- **Role-Based Access**: Admin and student roles with different permissions.
- **Student Admissions**: Students can submit admission profiles; admins can review and update statuses.
- **Profile Management**: Students can view and update their profiles.
- **Session-Based Authentication**: Secure PHP sessions for user authentication.
- **Password Security**: Passwords are hashed using Argon2i.
- **CORS Support**: Configured for local frontend-backend development.
- **Extensible**: Designed for future integration with AWS KMS for PII encryption.

---

## Getting Started

### Prerequisites

- PHP 7.4+ with `pdo_mysql` extension
- MySQL or MariaDB
- Node.js (for frontend)
- Composer (optional, for AWS SDK if using AWS Secrets Manager)

### Backend Setup (phpserver)

1.  **Database Setup**
    - Create a MySQL database (default: `charusat`).
    - Import the schema from `phpserver/schemas/` if available.
    - Update credentials in `src/Config/.env.local.php` for local development.

2.  **Configure Environment**
    - For local dev, use `.env.local.php`.
    - For production, set environment variables (e.g., for AWS Secrets Manager).

3.  **Run PHP Server**
    - From the `phpserver/` directory, run the built-in PHP server:
      ```sh
      php -S localhost:8000 -t .
      ```

### Frontend Setup (frontend)

1.  **Install Dependencies**
    ```sh
    cd frontend
    npm install
    ```

2.  **Start Development Server**
    ```sh
    npm start
    ```
    or, if using Vite:
    ```sh
    npm run dev
    ```

3.  **Build for Production**
    ```sh
    npm run build
    ```

---

## API Overview

See `API_DOC.md` for full details on request/response formats.

### Key Endpoints

- **`POST /api/auth/register.php`**: Register a new user (student or admin).
- **`POST /api/auth/login.php`**: Login and start a session.
- **`POST /api/auth/logout.php`**: Logout and destroy the session.
- **`GET /api/auth/me.php`**: Get current session user info.
- **`POST /api/student/admissions.php`**: Create a student admission profile.
- **`GET /api/student/profile.php?student_id=...`**: Get a student profile.
- **`PUT /api/student/profile.php`**: Update a student profile.

---

## Security

- Passwords are never stored in plaintext; they are hashed with **Argon2i**.
- All API endpoints validate input and return structured JSON responses.
- Secure, server-side sessions are used for authentication.
- CORS headers are configured for local development security.
- (Planned) Integration with **AWS KMS** for encrypting sensitive PII fields.

---

## Database Schema

See `API_DOC.md` for detailed table definitions and relationships.

---

## Example Usage

### Register a Student

```sh
curl -X POST http://localhost:8000/api/auth/register.php \
 -H "Content-Type: application/json" \
 -d '{"email":"student@example.com","password":"Password123!","full_name":"Student Name","role":"student"}'

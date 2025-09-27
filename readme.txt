# Multi-Role ERP Platform

## Overview

This project is a full-stack ERP (Enterprise Resource Planning) platform designed for educational institutions. It supports multiple user roles (admin, student) and provides secure authentication, student admissions, and profile management. The backend is built with PHP (using PDO for database access), and the frontend is a modern JavaScript application (React or Vite-based).

---

## Folder Structure

```
frontend/
  build/           # Production build output
  public/          # Static public assets
  src/             # Frontend source code
  package.json     # Frontend dependencies and scripts
  server.js        # (Optional) Node.js server for frontend dev/proxy

phpserver/
  api/             # PHP API endpoints (auth, student, etc.)
    auth/
      login.php
      logout.php
      register.php
      me.php
    student/
      admissions.php
      profile.php
  src/             # PHP source (Auth, Config, Database)
    Auth/
      AuthHelper.php
    Config/
      config.php
      .env.local.php
    Database/
      DBConnection.php
  schemas/         # (Optional) Database schema files
  API_DOC.md       # Backend API documentation
```

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

- PHP 7.4+ with PDO MySQL extension
- MySQL or MariaDB
- Node.js (for frontend)
- Composer (optional, for AWS SDK if using AWS Secrets Manager)

### Backend Setup (phpserver)

1. **Database Setup**
   - Create a MySQL database (default: `charusat`).
   - Import the schema from `phpserver/schemas/` if available.
   - Update credentials in [`src/Config/.env.local.php`](phpserver/src/Config/.env.local.php) for local development.

2. **Configure Environment**
   - For local dev, use `.env.local.php`.
   - For production, set AWS Secrets Manager environment variables.

3. **Run PHP Server**
   - From `phpserver/`, run:
     ```sh
     php -S localhost:8000 -t .
     ```

### Frontend Setup (frontend)

1. **Install Dependencies**
   ```sh
   cd frontend
   npm install
   ```

2. **Start Development Server**
   ```sh
   npm start
   ```
   or, if using Vite:
   ```sh
   npm run dev
   ```

3. **Build for Production**
   ```sh
   npm run build
   ```

---

## API Overview

See [`API_DOC.md`](phpserver/API_DOC.md) for full details.

### Key Endpoints

- **POST `/api/auth/register.php`**  
  Register a new user (student or admin).

- **POST `/api/auth/login.php`**  
  Login and start a session.

- **POST `/api/student/admissions.php`**  
  Create a student admission profile.

- **GET `/api/student/profile.php?student_id=...`**  
  Get a student profile.

- **PUT `/api/student/profile.php`**  
  Update a student profile.

- **GET `/api/auth/me.php`**  
  Get current session user info.

- **POST `/api/auth/logout.php`**  
  Logout and destroy session.

---

## Security

- Passwords are hashed with Argon2i.
- All endpoints validate input and return JSON.
- Sessions are used for authentication.
- CORS headers are set for local development.
- (Planned) AWS KMS for encrypting PII fields.

---

## Database Schema

See [`API_DOC.md`](phpserver/API_DOC.md) for table definitions.

---

## Example Usage

**Register a Student**
```sh
curl -X POST http://localhost:8000/api/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{"email":"student@example.com","password":"Password123!","full_name":"Student Name","role":"student"}'
```

**Login**
```sh
curl -X POST http://localhost:8000/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"email":"student@example.com","password":"Password123!"}'
```

**Create Student Profile**
```sh
curl -X POST http://localhost:8000/api/student/admissions.php \
  -H "Content-Type: application/json" \
  -d '{"student_id":"student@example.com","department":"CSE","admission_year":"2025","date_of_admission":"2025-09-01"}'
```

---

## Contributing

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Submit a pull request with a clear description.

---

## License

This project is for educational use. See LICENSE file if present.

---

## Contact

For questions or issues, contact the backend maintainer.
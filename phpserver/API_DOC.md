# PHP Backend API Documentation

## Overview
This backend provides secure authentication and student management for the Admin/Student Portal. It is built with PHP (PDO, modular controllers), uses Argon2i password hashing, and is designed for future AWS KMS integration for PII security.

---

## Folder Structure

```
phpserver/
  api/
    auth/
      register.php
      login.php
    student/
      admissions.php
  src/
    Auth/
      AuthHelper.php
    ...
  config.php
  ...
```

---

## API Endpoints

### 1. Registration
- **Endpoint:** `/api/auth/register.php`
- **Method:** POST
- **Request Body (JSON):**
  ```json
  {
    "email": "student@example.com",
    "password": "Password123!",
    "full_name": "Student Name",
    "role": "student" | "admin"
  }
  ```
- **Response (Success):**
  ```json
  { "success": true, "message": "Registration successful" }
  ```
- **Response (Error):**
  ```json
  { "success": false, "error": "Reason for failure" }
  ```
- **Notes:**
  - Passwords are hashed with Argon2i.
  - Duplicate emails are rejected.

---

### 2. Login
- **Endpoint:** `/api/auth/login.php`
- **Method:** POST
- **Request Body (JSON):**
  ```json
  {
    "email": "student@example.com",
    "password": "Password123!"
  }
  ```
- **Response (Success):**
  ```json
  { "success": true, "role": "student", "user": { "email": "...", "full_name": "..." } }
  ```
- **Response (Error):**
  ```json
  { "success": false, "error": "Invalid credentials" }
  ```
- **Notes:**
  - On success, a session is started and user info is returned.

---

### 3. Student Profile Creation (Admissions)
- **Endpoint:** `/api/student/admissions.php`
- **Method:** POST
- **Request Body (JSON):**
  ```json
  {
    "student_id": "student@example.com", // or unique student ID
    "department": "CSE",
    "admission_year": "2025",
    "date_of_admission": "2025-09-01"
  }
  ```
- **Response (Success):**
  ```json
  { "success": true, "message": "Profile created" }
  ```
- **Response (Error):**
  ```json
  { "success": false, "error": "Reason for failure" }
  ```

---

## Security
- Passwords are hashed with Argon2i.
- All endpoints validate input and return JSON.
- Sessions are used for authentication.
- (Planned) AWS KMS for encrypting PII fields.

---

## Database Tables

### users
| Field      | Type         | Notes                |
|------------|--------------|----------------------|
| id         | INT, PK      | Auto-increment       |
| email      | VARCHAR      | Unique, required     |
| password   | VARCHAR      | Argon2i hash         |
| full_name  | VARCHAR      |                      |
| role       | VARCHAR      | 'student' or 'admin' |

### admissions
| Field             | Type      | Notes                |
|-------------------|-----------|----------------------|
| id                | INT, PK   | Auto-increment       |
| student_id        | VARCHAR   | FK to users.email    |
| department        | VARCHAR   |                      |
| admission_year    | YEAR      |                      |
| date_of_admission | DATE      |                      |

---

## AuthHelper.php
- Handles password hashing, verification, and session logic.
- Usage:
  - `AuthHelper::hashPassword($password)`
  - `AuthHelper::verifyPassword($password, $hash)`
  - `AuthHelper::startSession($user)`

---

## Error Handling
- All endpoints return JSON with `success: false` and an `error` message on failure.
- HTTP status codes are set appropriately (e.g., 400 for bad request, 401 for unauthorized).

---

## Future Enhancements
- Integrate AWS KMS for PII encryption.
- Add admin endpoints for searching/filtering student records.
- Implement JWT-based authentication (optional).

---

## Example Usage

### Register a Student
```bash
curl -X POST http://localhost:8000/api/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{"email":"student@example.com","password":"Password123!","full_name":"Student Name","role":"student"}'
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"email":"student@example.com","password":"Password123!"}'
```

### Create Student Profile
```bash
curl -X POST http://localhost:8000/api/student/admissions.php \
  -H "Content-Type: application/json" \
  -d '{"student_id":"student@example.com","department":"CSE","admission_year":"2025","date_of_admission":"2025-09-01"}'
```

---

## Contact
For questions or issues, contact the backend maintainer.

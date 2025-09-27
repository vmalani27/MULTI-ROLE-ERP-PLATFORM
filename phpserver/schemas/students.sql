-- students.sql: Comprehensive student profile table
CREATE TABLE students (
    user_id VARCHAR(50) PRIMARY KEY,         -- Unique user ID
    email VARCHAR(255) NOT NULL UNIQUE,         -- Email address
    full_name VARCHAR(255) NOT NULL,            -- Student's full name
    department VARCHAR(50) NOT NULL,            -- CSE, IT, CE, etc.
    admission_year YEAR NOT NULL,               -- Year of admission
    date_of_admission DATE NOT NULL,            -- Date of admission
    phone VARCHAR(20) NOT NULL,                 -- Student's phone number
    parent_name VARCHAR(255) NOT NULL,          -- Parent's name
    parent_phone VARCHAR(20) NOT NULL,          -- Parent's phone number
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

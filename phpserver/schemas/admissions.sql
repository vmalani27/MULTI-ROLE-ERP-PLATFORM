-- admissions.sql: Schema for admissions table
CREATE TABLE admissions (
    user_id VARCHAR(32) PRIMARY KEY,
    department VARCHAR(50) NOT NULL,
    admission_year YEAR NOT NULL,
    date_of_admission DATE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    parent_name VARCHAR(255) NOT NULL,
    parent_phone VARCHAR(20) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE DATABASE complaint_system;
USE complaint_system;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Complaints table
CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    sub_category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
    admin_response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample complaints for testing
INSERT INTO complaints (user_id, category, sub_category, description, status, admin_response, created_at) VALUES
(1, 'Hardware Issue', 'Computer not turning on', 'My laptop won\'t turn on, no lights or sounds', 'Pending', 'Your ticket has been received and is awaiting assignment', '2023-05-15 10:00:00'),
(1, 'Account Access', 'Password reset', 'Can\'t access email account, password not working', 'In Progress', 'We\'re resetting your password, you\'ll receive an email shortly', '2023-05-14 12:00:00'),
(1, 'Hardware Issue', 'Printer in office 302 is jammed', 'Printer in office 302 is jammed', 'Resolved', 'Printer has been fixed and is working normally now', '2023-05-12 09:00:00'),
(1, 'Software Issue', 'Software X crashes when opening large files', 'Software X crashes when opening large files', 'Pending', 'Your ticket is in queue for technical support', '2023-05-16 14:00:00');
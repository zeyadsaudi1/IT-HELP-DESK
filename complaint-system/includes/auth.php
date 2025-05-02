<?php
// auth.php - Authentication functions

/**
 * Check if a user is logged in
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if the logged in user is an admin
 * @return bool True if user is admin, false otherwise
 */
function isAdmin() {
    // Check if user is logged in and has admin privileges
    // This can be implemented in different ways:
    // 1. Check against a predefined admin email (as defined in db_connect.php)
    if (isset($_SESSION['email']) && $_SESSION['email'] === ADMIN_EMAIL) {
        return true;
    }
    
    // 2. Or you could have an 'is_admin' column in your users table
    // if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    //     return true;
    // }
    
    return false;
}

/**
 * Sanitize user input to prevent XSS attacks
 * @param string $data The input to sanitize
 * @return string The sanitized input
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to login page if user is not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Redirect to dashboard if user is already logged in
 */
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header("Location: dashboard.php");
        exit();
    }
}

/**
 * Check if the current user is the owner of a complaint
 * @param int $complaintUserId The user_id associated with the complaint
 * @return bool True if current user owns the complaint, false otherwise
 */
function isComplaintOwner($complaintUserId) {
    return isLoggedIn() && $_SESSION['user_id'] == 1;
}
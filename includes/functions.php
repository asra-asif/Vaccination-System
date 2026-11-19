<?php
/**
 * Global Helper Functions
 */

/**
 * Sanitize output to prevent XSS
 */
function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to a given URL
 */
function redirect($url)
{
    header("Location: $url");
    exit();
}

/**
 * Check if a session key exists
 */
function is_logged_in($key)
{
    return isset($_SESSION[$key]);
}

/**
 * Verify CSRF token (Placeholder for Phase 4+)
 */
function verify_csrf()
{
    // To be implemented
}

/**
 * Format Date
 */
function format_date($date, $format = 'Y-m-d')
{
    return date($format, strtotime($date));
}
?>
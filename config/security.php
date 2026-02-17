<?php
/**
 * Security Utilities for Kreasi Pro
 * 
 * This file provides essential security functions for:
 * - Output encoding (XSS prevention)
 * - Input sanitization
 * - Security headers
 * - CSRF protection
 * 
 * @package KreasiPro
 * @author Security Team
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('KREASI_PRO_LOADED')) {
    die('Direct access not permitted');
}

/**
 * Escape HTML output to prevent XSS
 * Use this for general HTML content
 * 
 * @param string $string The string to escape
 * @return string Escaped string
 */
function escapeHtml($string)
{
    if ($string === null || $string === '') {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Escape HTML attributes to prevent XSS
 * Use this for HTML attribute values
 * 
 * @param string $string The string to escape
 * @return string Escaped string
 */
function escapeAttr($string)
{
    if ($string === null || $string === '') {
        return '';
    }
    // More aggressive encoding for attributes
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Escape JavaScript strings
 * Use this when embedding PHP values in JavaScript
 * 
 * @param string $string The string to escape
 * @return string Escaped string
 */
function escapeJs($string)
{
    if ($string === null || $string === '') {
        return '';
    }
    // Encode for JavaScript context
    return addslashes($string);
}

/**
 * Escape URLs to prevent XSS in href/src attributes
 * 
 * @param string $url The URL to escape
 * @return string Escaped URL
 */
function escapeUrl($url)
{
    if ($url === null || $url === '') {
        return '';
    }
    
    // Block dangerous protocols
    $dangerous_protocols = ['javascript:', 'data:', 'vbscript:', 'file:'];
    $lower_url = strtolower(trim($url));
    
    foreach ($dangerous_protocols as $protocol) {
        if (strpos($lower_url, $protocol) === 0) {
            return '#'; // Return safe default
        }
    }
    
    return htmlspecialchars($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanitize general input
 * Removes dangerous characters while preserving intended content
 * 
 * @param string $input The input to sanitize
 * @return string Sanitized input
 */
function sanitizeInput($input)
{
    if ($input === null || $input === '') {
        return '';
    }
    
    // Remove null bytes
    $input = str_replace("\0", '', $input);
    
    // Trim whitespace
    $input = trim($input);
    
    return $input;
}

/**
 * Sanitize filename to prevent path traversal
 * 
 * @param string $filename The filename to sanitize
 * @return string Sanitized filename
 */
function sanitizeFilename($filename)
{
    if ($filename === null || $filename === '') {
        return '';
    }
    
    // Remove path traversal attempts
    $filename = str_replace(['..', '/', '\\'], '', $filename);
    
    // Remove special characters except alphanumeric, dash, underscore, and dot
    $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $filename);
    
    return $filename;
}

/**
 * Validate and sanitize HTTP Host header
 * Prevents HTTP Host Header Injection
 * 
 * @param string $host The host to validate
 * @return string|false Validated host or false if invalid
 */
function validateHost($host)
{
    // Remove port if present
    $host = preg_replace('/:\d+$/', '', $host);
    
    // Validate hostname format
    if (!preg_match('/^[a-zA-Z0-9\-\.]+$/', $host)) {
        return false;
    }
    
    return $host;
}

/**
 * Get safe base URL
 * Constructs base URL with validation to prevent injection
 * 
 * @return string Safe base URL
 */
function getSafeBaseUrl()
{
    // Determine protocol
    $isHttps = false;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $isHttps = true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        $isHttps = true;
    }
    
    $protocol = $isHttps ? 'https' : 'http';
    
    // Validate and get host
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $validatedHost = validateHost($host);
    
    if ($validatedHost === false) {
        // Fallback to SERVER_NAME if HTTP_HOST is invalid
        $host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
        $validatedHost = validateHost($host);
        if ($validatedHost === false) {
            $validatedHost = 'localhost'; // Ultimate fallback
        }
    }
    
    // Get script directory
    $scriptDir = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
    $scriptDir = rtrim($scriptDir, '/\\');
    
    return $protocol . '://' . $validatedHost . $scriptDir;
}

/**
 * Generate CSRF Token
 * Creates a secure random token for CSRF protection
 * 
 * @return string CSRF token
 */
function generateCsrfToken()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF Token
 * Verifies submitted CSRF token matches session token
 * 
 * @param string $token Token to validate
 * @return bool True if valid, false otherwise
 */
function validateCsrfToken($token)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Set security headers
 * Adds HTTP security headers to response
 * 
 * @return void
 */
function setSecurityHeaders()
{
    // Prevent clickjacking
    header('X-Frame-Options: SAMEORIGIN');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Enable XSS filter in browsers
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy (relaxed for compatibility)
    $csp = "default-src 'self'; " .
           "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://ajax.googleapis.com https://cdn.jsdelivr.net; " .
           "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://use.fontawesome.com; " .
           "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://use.fontawesome.com; " .
           "img-src 'self' data: https:; " .
           "frame-src https://www.google.com; " .
           "connect-src 'self';";
    
    header("Content-Security-Policy: $csp");
    
    // HSTS (only if HTTPS)
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

/**
 * Sanitize array recursively
 * Applies sanitization to all array values
 * 
 * @param array $array The array to sanitize
 * @return array Sanitized array
 */
function sanitizeArray($array)
{
    $sanitized = [];
    
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $sanitized[$key] = sanitizeArray($value);
        } else {
            $sanitized[$key] = sanitizeInput($value);
        }
    }
    
    return $sanitized;
}

/**
 * Check if request is POST
 * 
 * @return bool True if POST request
 */
function isPostRequest()
{
    return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Check if request is AJAX
 * 
 * @return bool True if AJAX request
 */
function isAjaxRequest()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

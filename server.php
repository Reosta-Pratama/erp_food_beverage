<?php

/**
 * ERP Food & Beverage Manufacturing System
 * 
 * Laravel PHP Framework Development Server
 * 
 * @author    REOSTA BAYU PRATAMA PANE
 * @link      https://www.linkedin.com/in/reosta-bayu-pratama-pane-b1b282223/
 * @github    https://github.com/Reosta-Pratama
 * @copyright 2025 REOSTA BAYU PRATAMA PANE. All Rights Reserved.
 * @version   1.0.0
 * 
 * This file allows us to emulate Apache's "mod_rewrite" functionality from the
 * built-in PHP web server. This provides a convenient way to test a Laravel
 * application without having installed a "real" web server software here.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Forward all other requests to Laravel's front controller
require_once __DIR__.'/public/index.php';
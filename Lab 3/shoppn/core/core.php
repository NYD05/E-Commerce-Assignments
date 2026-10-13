<?php
// core/core.php — loaded first by every page and action.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Africa/Accra');

require_once __DIR__ . '/db_class.php';

/*
 * BASE_URL — the web path to the project folder, worked out automatically, e.g.
 *   XAMPP:          /shoppn
 *   school server:  /~your.name/shoppn
 * so links and redirects work wherever the folder is uploaded.
 * Build every link as  BASE_URL . '/views/login.php'  (never hardcode "/shoppn/...").
 */
if (!defined('BASE_URL')) {
    $projectRoot = str_replace('\\', '/', (string)realpath(dirname(__DIR__)));
    $scriptFile  = str_replace('\\', '/', (string)realpath($_SERVER['SCRIPT_FILENAME'] ?? ''));
    $scriptName  = $_SERVER['SCRIPT_NAME'] ?? '';
    $baseUrl     = '/shoppn'; // fallback

    if ($projectRoot !== '' && $scriptFile !== '' && stripos($scriptFile, $projectRoot) === 0) {
        $relative = substr($scriptFile, strlen($projectRoot));        // e.g. /views/register.php
        $len      = strlen($relative);
        if ($len > 0 && strcasecmp(substr($scriptName, -$len), $relative) === 0) {
            $baseUrl = substr($scriptName, 0, -$len);                  // e.g. /~your.name/shoppn
        }
    }

    define('BASE_URL', rtrim($baseUrl, '/'));
    unset($projectRoot, $scriptFile, $scriptName, $baseUrl, $relative, $len);
}

function get_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['customer_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
}

function require_login() {
    if (!is_logged_in()) {
        $_SESSION['error'] = 'Please log in to continue.';
        redirect(BASE_URL . '/views/login.php');
    }
}

function require_admin() {
    if (!is_admin()) {
        $_SESSION['error'] = 'Admin access required.';
        redirect(BASE_URL . '/index.php');
    }
}

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/login.php');
}

// Get form data
$email = trim($_POST['email'] ?? '');
$pass = $_POST['pass'] ?? '';

// Validate fields
if ($email === '' || $pass === '') {
    $_SESSION['error'] = 'Please enter your email and password.';
    redirect('../views/login.php');
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect('../views/login.php');
}

// Attempt login
$controller = new CustomerController();

$result = $controller->login($email, $pass);

if ($result['success']) {

    $customer = $result['customer'];

    // Store customer information in the session
    $_SESSION['customer_id'] = $customer['customer_id'];
    $_SESSION['customer_name'] = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role'] = (int)$customer['user_role'];

    // Redirect to homepage
    redirect('../index.php');

} else {

    $_SESSION['error'] = $result['message'];

    redirect('../views/login.php');
}

?>
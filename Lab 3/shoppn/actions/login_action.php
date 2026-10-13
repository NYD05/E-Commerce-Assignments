<?php


require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/views/login.php');
}

// Get form data
$email = trim($_POST['email'] ?? '');
$pass = $_POST['pass'] ?? '';

// Validate fields
if ($email === '' || $pass === '') {
    $_SESSION['error'] = 'Please enter your email and password.';
    redirect(BASE_URL . '/views/login.php');
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect(BASE_URL . '/views/login.php');
}

// Attempt login
$controller = new CustomerController();

$result = $controller->login($email, $pass);

if ($result['success']) {

    $customer = $result['customer'];

    // New session ID after login (prevents session fixation)
    session_regenerate_id(true);

    // Store customer information in the session
    $_SESSION['customer_id'] = $customer['customer_id'];
    $_SESSION['customer_name'] = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role'] = (int)$customer['user_role'];

    // Redirect to homepage
    redirect(BASE_URL . '/index.php');

} else {

    $_SESSION['error'] = $result['message'];

    redirect(BASE_URL . '/views/login.php');
}

?>
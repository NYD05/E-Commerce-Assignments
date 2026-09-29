<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/register.php');
}

$name = trim(strip_tags($_POST['name'] ?? ''));
$email = trim($_POST['email'] ?? '');
$pass = $_POST['pass'] ?? '';
$country = trim(strip_tags($_POST['country'] ?? ''));
$city = trim(strip_tags($_POST['city'] ?? ''));
$contact = trim(strip_tags($_POST['contact'] ?? ''));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect('../views/register.php');
}

if (
    $name === '' ||
    $pass === '' ||
    $country === '' ||
    $city === '' ||
    $contact === ''
) {
    $_SESSION['error'] = 'Please fill in all required fields.';
    redirect('../views/register.php');
}

if (strlen($name) > 100) {
    $_SESSION['error'] = 'Name must not exceed 100 characters.';
    redirect('../views/register.php');
}

if (strlen($email) > 50) {
    $_SESSION['error'] = 'Email must not exceed 50 characters.';
    redirect('../views/register.php');
}

if (strlen($country) > 30) {
    $_SESSION['error'] = 'Country must not exceed 30 characters.';
    redirect('../views/register.php');
}

if (strlen($city) > 30) {
    $_SESSION['error'] = 'City must not exceed 30 characters.';
    redirect('../views/register.php');
}

if (strlen($contact) > 15) {
    $_SESSION['error'] = 'Contact number must not exceed 15 characters.';
    redirect('../views/register.php');
}

$data = [
    'name' => $name,
    'email' => $email,
    'pass' => $pass,
    'country' => $country,
    'city' => $city,
    'contact' => $contact
];

$controller = new CustomerController();

$result = $controller->register($data);

if ($result['success']) {

    $customerId = $controller->getCustomerIdByEmail($email);

    $_SESSION['customer_id'] = $customerId;
    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;
    $_SESSION['user_role'] = 2;

    redirect('../views/account/my_account.php');

} else {

    $_SESSION['error'] = $result['message'];

    redirect('../views/register.php');
}

?>
<?php
// actions/add_brand_action.php
require_once __DIR__ . '/../core/core.php';
require_admin();

require_once __DIR__ . '/../controllers/ProductController.php';

$redirect = BASE_URL . '/views/admin/brand.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $redirect");
    exit;
}

$name = trim(strip_tags($_POST['brand_name'] ?? ''));

if ($name === '' || mb_strlen($name) > 100) {
    $_SESSION['error'] = 'Brand name is required (max 100 characters).';
    header("Location: $redirect");
    exit;
}

try {
    $controller = new ProductController();
    if ($controller->addBrand($name)) {
        $_SESSION['success'] = 'Brand added.';
    } else {
        $_SESSION['error'] = 'Could not add brand.';
    }
} catch (mysqli_sql_exception $e) {
    // 1062 = duplicate entry (if brand_name is UNIQUE)
    $_SESSION['error'] = $e->getCode() == 1062 ? 'That brand already exists.' : 'Database error adding brand.';
}

header("Location: $redirect");
exit;

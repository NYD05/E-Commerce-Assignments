<?php
// actions/add_category_action.php
require_once __DIR__ . '/../core/core.php';
require_admin();

require_once __DIR__ . '/../controllers/ProductController.php';

$redirect = BASE_URL . '/views/admin/category.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $redirect");
    exit;
}

$name = trim(strip_tags($_POST['cat_name'] ?? ''));

if ($name === '' || mb_strlen($name) > 100) {
    $_SESSION['error'] = 'Category name is required (max 100 characters).';
    header("Location: $redirect");
    exit;
}

try {
    $controller = new ProductController();
    if ($controller->addCategory($name)) {
        $_SESSION['success'] = 'Category added.';
    } else {
        $_SESSION['error'] = 'Could not add category.';
    }
} catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = $e->getCode() == 1062 ? 'That category already exists.' : 'Database error adding category.';
}

header("Location: $redirect");
exit;

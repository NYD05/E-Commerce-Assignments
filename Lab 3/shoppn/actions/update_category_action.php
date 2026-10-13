<?php
// actions/update_category_action.php
require_once __DIR__ . '/../core/core.php';
require_admin();

require_once __DIR__ . '/../controllers/ProductController.php';

$redirect = BASE_URL . '/views/admin/category.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $redirect");
    exit;
}

$id   = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$name = trim(strip_tags($_POST['cat_name'] ?? ''));

if (!$id) {
    $_SESSION['error'] = 'Invalid category.';
    header("Location: $redirect");
    exit;
}
if ($name === '' || mb_strlen($name) > 100) {
    $_SESSION['error'] = 'Category name is required (max 100 characters).';
    header("Location: $redirect?edit_id=$id");
    exit;
}

try {
    $controller = new ProductController();
    if ($controller->updateCategory($id, $name)) {
        $_SESSION['success'] = 'Category updated.';
    } else {
        $_SESSION['error'] = 'Could not update category.';
    }
} catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = $e->getCode() == 1062 ? 'That category already exists.' : 'Database error updating category.';
}

header("Location: $redirect");
exit;

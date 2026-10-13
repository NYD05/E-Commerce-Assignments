<?php
// actions/update_brand_action.php
require_once __DIR__ . '/../core/core.php';
require_admin();

require_once __DIR__ . '/../controllers/ProductController.php';

$redirect = BASE_URL . '/views/admin/brand.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $redirect");
    exit;
}

$id   = filter_input(INPUT_POST, 'brand_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$name = trim(strip_tags($_POST['brand_name'] ?? ''));

if (!$id) {
    $_SESSION['error'] = 'Invalid brand.';
    header("Location: $redirect");
    exit;
}
if ($name === '' || mb_strlen($name) > 100) {
    $_SESSION['error'] = 'Brand name is required (max 100 characters).';
    header("Location: $redirect?edit_id=$id");
    exit;
}

try {
    $controller = new ProductController();
    if ($controller->updateBrand($id, $name)) {
        $_SESSION['success'] = 'Brand updated.';
    } else {
        $_SESSION['error'] = 'Could not update brand.';
    }
} catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = $e->getCode() == 1062 ? 'That brand already exists.' : 'Database error updating brand.';
}

header("Location: $redirect");
exit;

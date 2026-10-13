<?php
// actions/add_product_action.php
require_once __DIR__ . '/../core/core.php';
require_admin();

require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/product_image_upload.php';

$redirect = BASE_URL . '/views/admin/product.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($redirect);
}

// A file bigger than PHP's post_max_size empties $_POST entirely — catch that first.
if (empty($_POST) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    $_SESSION['error'] = 'The upload was too large.';
    redirect($redirect);
}

[$data, $error] = validate_product_fields();
if ($error) {
    $_SESSION['error'] = $error;
    redirect($redirect);
}

$upload = handle_product_image_upload();
if ($upload['error']) {
    $_SESSION['error'] = $upload['error'];
    redirect($redirect);
}
if ($upload['filename'] === null) {
    $_SESSION['error'] = 'Please choose a product image.';
    redirect($redirect);
}

try {
    $controller = new ProductController();
    $ok = $controller->addProduct(
        $data['cat'], $data['brand'], $data['title'], $data['price'],
        $data['desc'], $upload['filename'], $data['keywords']
    );
    $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Product added.' : 'Could not add product.';
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Database error adding product. Check the category and brand exist.';
}

redirect($redirect);

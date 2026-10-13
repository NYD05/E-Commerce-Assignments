<?php
// actions/update_product_action.php
require_once __DIR__ . '/../core/core.php';
require_admin();

require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/product_image_upload.php';

$redirect = BASE_URL . '/views/admin/product.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($redirect);
}

if (empty($_POST) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    $_SESSION['error'] = 'The upload was too large.';
    redirect($redirect);
}

$id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) {
    $_SESSION['error'] = 'Invalid product.';
    redirect($redirect);
}

$editUrl = $redirect . '?edit_id=' . $id;

[$data, $error] = validate_product_fields();
if ($error) {
    $_SESSION['error'] = $error;
    redirect($editUrl);
}

$controller = new ProductController();
$existing   = $controller->getProductById($id);
if (!$existing) {
    $_SESSION['error'] = 'Product not found.';
    redirect($redirect);
}

$upload = handle_product_image_upload();
if ($upload['error']) {
    $_SESSION['error'] = $upload['error'];
    redirect($editUrl);
}

// New image uploaded → use it. Otherwise keep the existing filename.
// (The form also sends it in a hidden field, but we take the trusted copy from the database
//  so nobody can point a product at someone else's file by editing the hidden field.)
$filename = $upload['filename'] ?? (string)$existing['product_image'];

try {
    $ok = $controller->updateProduct(
        $id, $data['cat'], $data['brand'], $data['title'], $data['price'],
        $data['desc'], $filename, $data['keywords']
    );
    $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Product updated.' : 'Could not update product.';
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Database error updating product.';
    redirect($editUrl);
}

redirect($redirect);

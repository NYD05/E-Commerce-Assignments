<?php
// actions/add_to_cart_action.php — triggered by "Add to Cart" links: ?add_cart=<product_id>
// Guests can add to cart too: rows are tied to the visitor's IP address.
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// Send the visitor back to the page they clicked from (only if it is on this site).
$redirect = BASE_URL . '/index.php';
$referer  = $_SERVER['HTTP_REFERER'] ?? '';
if ($referer !== '') {
    $refHost = parse_url($referer, PHP_URL_HOST);
    $refPath = parse_url($referer, PHP_URL_PATH) ?? '';
    if ($refHost === parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST)
        && strpos($refPath, BASE_URL . '/') === 0
        && strpos($refPath, '/actions/') === false) {
        $redirect = $referer;
    }
}

$productId = filter_input(INPUT_GET, 'add_cart', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$productId) {
    $_SESSION['error'] = 'Invalid product.';
    redirect($redirect);
}

try {
    // Make sure the product actually exists before putting it in the cart.
    if (!(new ProductController())->getProductById($productId)) {
        $_SESSION['error'] = 'That product does not exist.';
        redirect($redirect);
    }

    $ip         = get_ip();
    $controller = new CartController();

    if ($controller->isInCart($productId, $ip)) {
        $_SESSION['error'] = 'This item is already in your cart. Go to cart to update quantity.';
    } elseif ($controller->addToCart($productId, 1, $ip, $_SESSION['customer_id'] ?? null)) {
        $_SESSION['success'] = 'Item added to your cart.';
    } else {
        $_SESSION['error'] = 'Could not add item to cart.';
    }
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Database error adding item to cart.';
}

redirect($redirect);

<?php
// actions/remove_from_cart_action.php — POST only. Removes the ticked items (remove[]) from the cart.
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$redirect = BASE_URL . '/views/cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($redirect);
}

$ids = $_POST['remove'] ?? [];
if (!is_array($ids)) {
    $ids = [$ids];
}

// Keep only positive integer product IDs.
$productIds = [];
foreach ($ids as $id) {
    $id = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id) {
        $productIds[] = $id;
    }
}

if (empty($productIds)) {
    $_SESSION['error'] = 'Tick at least one item to remove.';
    redirect($redirect);
}

try {
    $controller = new CartController();
    $ip         = get_ip();
    $removed    = 0;
    foreach (array_unique($productIds) as $id) {
        if ($controller->removeFromCart($id, $ip)) {
            $removed++;
        }
    }
    if ($removed > 0) {
        $_SESSION['success'] = $removed === 1 ? 'Item removed from your cart.' : "$removed items removed from your cart.";
    } else {
        $_SESSION['error'] = 'Could not remove the selected items.';
    }
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Database error removing items.';
}

redirect($redirect);

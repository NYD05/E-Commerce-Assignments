<?php
// actions/update_qty_action.php — POST only. Loops through qty[] and update[] (product IDs)
// and saves each new quantity. qty[i] belongs to update[i].
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$redirect = BASE_URL . '/views/cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($redirect);
}

$quantities = $_POST['qty'] ?? [];
$productIds = $_POST['update'] ?? [];

if (!is_array($quantities) || !is_array($productIds) || empty($productIds)
    || count($quantities) !== count($productIds)) {
    $_SESSION['error'] = 'Nothing to update.';
    redirect($redirect);
}

$quantities = array_values($quantities);
$productIds = array_values($productIds);

try {
    $controller = new CartController();
    $ip         = get_ip();
    $invalid    = 0;

    foreach ($productIds as $i => $rawId) {
        $id  = filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $qty = filter_var(trim((string)$quantities[$i]), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if (!$id || !$qty) {
            $invalid++;           // skip anything that isn't a positive whole number
            continue;
        }
        $controller->updateQty($id, $qty, $ip);
    }

    if ($invalid > 0) {
        $_SESSION['error'] = 'Quantities must be whole numbers of 1 or more. '
            . ($invalid === 1 ? '1 item was' : "$invalid items were") . ' not updated.';
    } else {
        $_SESSION['success'] = 'Cart quantities updated.';
    }
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Database error updating quantities.';
}

redirect($redirect);

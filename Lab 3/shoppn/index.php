<?php

require_once __DIR__ . '/core/core.php';

// Old-style links (index.php?add_cart=N) are passed on to the Add to Cart action.
if (isset($_GET['add_cart'])) {
    redirect(BASE_URL . '/actions/add_to_cart_action.php?add_cart=' . (int)$_GET['add_cart']);
}

require_once __DIR__ . '/views/home.php';

?>
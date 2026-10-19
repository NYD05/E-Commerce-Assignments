<?php
// views/cart.php — the shopping cart. The View only displays data; the two Actions
// (remove_from_cart_action.php and update_qty_action.php) do the work.
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$controller = new CartController();
$ip         = get_ip();
$items      = $controller->getCartItems($ip);
$subtotal   = $controller->getCartTotal($ip);

function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

require_once __DIR__ . '/layout/header.php';
?>

<main>

    <h1>Your Shopping Cart</h1>

    <?php if (empty($items)): ?>

        <p>Your cart is empty.</p>
        <p><a class="button" href="<?= BASE_URL ?>/index.php">Continue Shopping</a></p>

    <?php else: ?>

        <!-- Two separate forms. The table inputs join a form through their form="" attribute,
             so the checkboxes go to the remove action and the quantities go to the update action. -->
        <form id="removeCartForm" method="POST" action="<?= BASE_URL ?>/actions/remove_from_cart_action.php"></form>
        <form id="updateCartForm" method="POST" action="<?= BASE_URL ?>/actions/update_qty_action.php"></form>

        <table class="cart-table">
            <thead>
                <tr>
                    <th>Remove</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Line Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php $pid = (int)$item['p_id']; ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="remove[]" value="<?= $pid ?>" form="removeCartForm"
                                   aria-label="Remove <?= h($item['product_title']) ?>">
                        </td>
                        <td>
                            <div class="cart-product">
                                <?php if (!empty($item['product_image'])): ?>
                                    <img src="<?= BASE_URL ?>/images/products/<?= h($item['product_image']) ?>"
                                         alt="<?= h($item['product_title']) ?>" width="60" height="60">
                                <?php else: ?>
                                    <div class="no-image cart-no-image">No image</div>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/views/single_product.php?pro_id=<?= $pid ?>"><?= h($item['product_title']) ?></a>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="qty-input" name="qty[]" value="<?= (int)$item['qty'] ?>"
                                   inputmode="numeric" pattern="[0-9]*" form="updateCartForm"
                                   aria-label="Quantity for <?= h($item['product_title']) ?>">
                            <input type="hidden" name="update[]" value="<?= $pid ?>" form="updateCartForm">
                        </td>
                        <td>GH₵ <?= number_format((float)$item['product_price'], 2) ?></td>
                        <td>GH₵ <?= number_format((int)$item['qty'] * (float)$item['product_price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="subtotal-row">
                    <td colspan="4">Subtotal</td>
                    <td>GH₵ <?= number_format($subtotal, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="cart-actions">
            <button type="submit" form="removeCartForm" class="btn-danger">Remove from Cart</button>
            <button type="submit" form="updateCartForm">Update Quantity</button>
            <a class="button btn-secondary" href="<?= BASE_URL ?>/index.php">Continue Shopping</a>
            <a class="button" href="<?= BASE_URL ?>/views/checkout.php">Checkout</a>
        </div>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

<?php
// views/layout/product_card.php — one product card. Shared by home.php and search_results.php
// so both pages show products in exactly the same format.
// Expects $p (a product row) to be set before this file is included.
?>
<div class="product-card">
    <?php if (!empty($p['product_image'])): ?>
        <img src="<?= BASE_URL ?>/images/products/<?= htmlspecialchars($p['product_image'], ENT_QUOTES, 'UTF-8') ?>"
             alt="<?= htmlspecialchars($p['product_title'], ENT_QUOTES, 'UTF-8') ?>">
    <?php else: ?>
        <div class="no-image">No image</div>
    <?php endif; ?>

    <h3><?= htmlspecialchars($p['product_title'], ENT_QUOTES, 'UTF-8') ?></h3>
    <p class="price">GH₵ <?= number_format((float)$p['product_price'], 2) ?></p>

    <p class="card-links">
        <a href="<?= BASE_URL ?>/views/single_product.php?pro_id=<?= (int)$p['product_id'] ?>">Details</a>
        <a href="<?= BASE_URL ?>/actions/add_to_cart_action.php?add_cart=<?= (int)$p['product_id'] ?>">Add to Cart</a>
    </p>
</div>

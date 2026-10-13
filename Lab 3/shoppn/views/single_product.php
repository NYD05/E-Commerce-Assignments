<?php
// views/single_product.php — one product's full details.
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$proId = filter_input(INPUT_GET, 'pro_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$proId) {
    redirect(BASE_URL . '/index.php');
}

$controller = new ProductController();
$product    = $controller->getProductById($proId);
if (!$product) {
    $_SESSION['error'] = 'That product does not exist.';
    redirect(BASE_URL . '/index.php');
}

function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/sidebar.php';
?>

<main class="with-sidebar">

    <p><a href="<?= BASE_URL ?>/index.php">&larr; Back to products</a></p>

    <div class="product-detail">
        <div>
            <?php if (!empty($product['product_image'])): ?>
                <img src="<?= BASE_URL ?>/images/products/<?= h($product['product_image']) ?>" alt="<?= h($product['product_title']) ?>">
            <?php else: ?>
                <div class="no-image">No image</div>
            <?php endif; ?>
        </div>

        <div>
            <h1><?= h($product['product_title']) ?></h1>
            <p class="price">GH₵ <?= number_format((float)$product['product_price'], 2) ?></p>

            <p>
                <strong>Category:</strong>
                <a href="<?= BASE_URL ?>/index.php?cat=<?= (int)$product['product_cat'] ?>"><?= h($product['cat_name'] ?? '-') ?></a>
            </p>
            <p>
                <strong>Brand:</strong>
                <a href="<?= BASE_URL ?>/index.php?brand=<?= (int)$product['product_brand'] ?>"><?= h($product['brand_name'] ?? '-') ?></a>
            </p>

            <h3>Description</h3>
            <p><?= nl2br(h($product['product_desc'])) ?></p>

            <?php if (!empty($product['product_keywords'])): ?>
                <p><strong>Keywords:</strong> <?= h($product['product_keywords']) ?></p>
            <?php endif; ?>

            <p><a class="button" href="<?= BASE_URL ?>/index.php?add_cart=<?= (int)$product['product_id'] ?>">Add to Cart</a></p>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

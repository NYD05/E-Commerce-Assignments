<?php
// views/home.php — loaded by index.php. The View asks the Controller for data.
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

$catId   = filter_input(INPUT_GET, 'cat',   FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$brandId = filter_input(INPUT_GET, 'brand', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($catId) {
    $products = $controller->getProductsByCategory($catId);
    $cat      = $controller->getCategoryById($catId);
    $heading  = 'Category: ' . ($cat['cat_name'] ?? 'Unknown');
} elseif ($brandId) {
    $products = $controller->getProductsByBrand($brandId);
    $brand    = $controller->getBrandById($brandId);
    $heading  = 'Brand: ' . ($brand['brand_name'] ?? 'Unknown');
} else {
    $products = $controller->getFeaturedProducts(6);
    $heading  = 'Featured Products';
}

$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);

require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/sidebar.php';
?>

<main class="with-sidebar">

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <h1><?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $p): ?>
                <?php include __DIR__ . '/layout/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

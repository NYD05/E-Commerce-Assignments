<?php
// views/search_results.php — products whose title or keywords match ?user_query.
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$query    = trim(strip_tags($_GET['user_query'] ?? ''));
$products = [];

if ($query !== '') {
    $controller = new ProductController();
    $products   = $controller->searchProducts(mb_substr($query, 0, 100));
}

require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/sidebar.php';
?>

<main class="with-sidebar">

    <?php if ($query === ''): ?>
        <h1>Search</h1>
        <p>Type something in the search box above to find products.</p>
    <?php else: ?>
        <h1>Results for "<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>"</h1>

        <?php if (empty($products)): ?>
            <p>No products found.</p>
        <?php else: ?>
            <p><?= count($products) ?> product<?= count($products) === 1 ? '' : 's' ?> found.</p>
            <div class="product-grid">
                <?php foreach ($products as $p): ?>
                    <?php include __DIR__ . '/layout/product_card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

<?php
// views/layout/sidebar.php — category & brand filters. Data comes through the Controller.
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$sidebarController = new ProductController();
$sidebarCategories = $sidebarController->getAllCategories();
$sidebarBrands     = $sidebarController->getAllBrands();
?>
<aside class="sidebar">

    <h3>Categories</h3>
    <?php if (empty($sidebarCategories)): ?>
        <p>No categories available yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($sidebarCategories as $c): ?>
                <li>
                    <a href="<?= BASE_URL ?>/index.php?cat=<?= (int)$c['cat_id'] ?>">
                        <?= htmlspecialchars($c['cat_name'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h3>Brands</h3>
    <?php if (empty($sidebarBrands)): ?>
        <p>No brands available yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($sidebarBrands as $b): ?>
                <li>
                    <a href="<?= BASE_URL ?>/index.php?brand=<?= (int)$b['brand_id'] ?>">
                        <?= htmlspecialchars($b['brand_name'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="<?= BASE_URL ?>/index.php">Show all</a></p>

</aside>

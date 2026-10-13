<?php
// views/admin/product.php — add + edit products. require_admin() BEFORE any HTML.
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';
$controller = new ProductController();

// Edit mode: the View only READS the product to pre-fill the form.
$editProduct = null;
$editId = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($editId) {
    $editProduct = $controller->getProductById($editId);
    if (!$editProduct) {
        $_SESSION['error'] = 'Product not found.';
    }
}

$categories = $controller->getAllCategories();
$brands     = $controller->getAllBrands();
$products   = $controller->getAllProducts();

$success = $_SESSION['success'] ?? null;
$error   = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

if (!function_exists('e')) {
    function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

// Helper: value to show in a field (edit mode → current value, add mode → empty).
$val = fn($field) => $editProduct[$field] ?? '';

require_once __DIR__ . '/../layout/header.php';
?>
<main style="padding: 30px; max-width: 900px;">
    <h1><?= $editProduct ? 'Edit Product' : 'Add Product' ?></h1>

    <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="error"><?= e($error) ?></div><?php endif; ?>

    <?php if (empty($categories) || empty($brands)): ?>
        <div class="error">
            Add at least one <a href="category.php">category</a> and one <a href="brand.php">brand</a>
            before adding products.
        </div>
    <?php endif; ?>

    <form id="productForm"
          method="POST"
          enctype="multipart/form-data"
          action="<?= BASE_URL ?>/actions/<?= $editProduct ? 'update_product_action.php' : 'add_product_action.php' ?>">

        <?php if ($editProduct): ?>
            <input type="hidden" name="product_id" value="<?= (int)$editProduct['product_id'] ?>">
            <input type="hidden" name="existing_image" value="<?= e($editProduct['product_image']) ?>">
        <?php endif; ?>

        <p>
            <label for="product_title">Product Title</label><br>
            <input type="text" id="product_title" name="product_title" maxlength="200" required
                   value="<?= e($val('product_title')) ?>" style="width: 100%; padding: 8px;">
        </p>

        <p>
            <label for="product_price">Price</label><br>
            <input type="number" id="product_price" name="product_price" min="0.01" step="0.01" required
                   value="<?= e($val('product_price')) ?>" style="padding: 8px;">
        </p>

        <p>
            <label for="product_cat">Category</label><br>
            <select id="product_cat" name="product_cat" required style="padding: 8px;">
                <option value="">-- Choose a category --</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['cat_id'] ?>"
                        <?= (int)$val('product_cat') === (int)$c['cat_id'] ? 'selected' : '' ?>>
                        <?= e($c['cat_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="product_brand">Brand</label><br>
            <select id="product_brand" name="product_brand" required style="padding: 8px;">
                <option value="">-- Choose a brand --</option>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= (int)$b['brand_id'] ?>"
                        <?= (int)$val('product_brand') === (int)$b['brand_id'] ? 'selected' : '' ?>>
                        <?= e($b['brand_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="product_desc">Description</label><br>
            <textarea id="product_desc" name="product_desc" rows="4"
                      style="width: 100%; padding: 8px;"><?= e($val('product_desc')) ?></textarea>
        </p>

        <p>
            <label for="product_keywords">Keywords <small>(comma separated, used by search)</small></label><br>
            <input type="text" id="product_keywords" name="product_keywords"
                   value="<?= e($val('product_keywords')) ?>" style="width: 100%; padding: 8px;">
        </p>

        <p>
            <label for="product_image">
                Product Image <small>(JPG, PNG, GIF or WEBP, max 2 MB<?= $editProduct ? ' — leave empty to keep the current image' : '' ?>)</small>
            </label><br>
            <input type="file" id="product_image" name="product_image"
                   accept="image/jpeg,image/png,image/gif,image/webp"
                   <?= $editProduct ? '' : 'required' ?>>
        </p>

        <?php if ($editProduct && $editProduct['product_image']): ?>
            <p>
                Current image:<br>
                <img id="currentImage" src="<?= BASE_URL ?>/images/products/<?= e($editProduct['product_image']) ?>"
                     alt="Current product image" style="max-width: 200px; border: 1px solid #ccc;">
            </p>
        <?php endif; ?>
        <p><img id="imagePreview" alt="" style="max-width: 200px; display: none;"></p>

        <button type="submit" style="padding: 10px 20px;">
            <?= $editProduct ? 'Update Product' : 'Add Product' ?>
        </button>
        <?php if ($editProduct): ?><a href="product.php">Cancel</a><?php endif; ?>
    </form>

    <h2 style="margin-top: 40px;">All Products</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Brand</th><th>Price</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($products)): ?>
            <tr><td colspan="6">No products yet.</td></tr>
        <?php else: foreach ($products as $p): ?>
            <tr>
                <td>
                    <?php if ($p['product_image']): ?>
                        <img src="<?= BASE_URL ?>/images/products/<?= e($p['product_image']) ?>" alt="" style="width: 60px;">
                    <?php endif; ?>
                </td>
                <td><?= e($p['product_title']) ?></td>
                <td><?= e($p['cat_name'] ?? '-') ?></td>
                <td><?= e($p['brand_name'] ?? '-') ?></td>
                <td><?= number_format((float)$p['product_price'], 2) ?></td>
                <td><a href="product.php?edit_id=<?= (int)$p['product_id'] ?>">Edit</a></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</main>

<script src="<?= BASE_URL ?>/js/product_validate.js"></script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

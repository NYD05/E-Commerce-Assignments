<?php
// views/admin/brand.php — add + edit brands. require_admin() BEFORE any HTML.
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';
$controller = new ProductController();

// Edit mode: View only READS the brand to pre-fill the form (no UPDATE here).
$editBrand = null;
$editId = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($editId) {
    $editBrand = $controller->getBrandById($editId);
    if (!$editBrand) {
        $_SESSION['error'] = 'Brand not found.';
    }
}

$brands = $controller->getAllBrands();

// Pull flash messages once, then clear them.
$success = $_SESSION['success'] ?? null;
$error   = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

require_once __DIR__ . '/../layout/header.php';
?>
<main style="padding: 30px;">
    <h1><?= $editBrand ? 'Edit Brand' : 'Brands' ?></h1>

    <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="error"><?= e($error) ?></div><?php endif; ?>

    <?php if ($editBrand): ?>
        <form method="POST" action="../../actions/update_brand_action.php">
            <input type="hidden" name="brand_id" value="<?= e($editBrand['brand_id']) ?>">
            <input type="text" name="brand_name" value="<?= e($editBrand['brand_name']) ?>" maxlength="100" required>
            <button type="submit">Update Brand</button>
            <a href="brand.php">Cancel</a>
        </form>
    <?php else: ?>
        <form method="POST" action="../../actions/add_brand_action.php">
            <input type="text" name="brand_name" placeholder="Brand name" maxlength="100" required>
            <button type="submit">Add Brand</button>
        </form>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 20px;">
        <thead><tr><th>#</th><th>Brand</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($brands)): ?>
            <tr><td colspan="3">No brands yet.</td></tr>
        <?php else: foreach ($brands as $b): ?>
            <tr>
                <td><?= e($b['brand_id']) ?></td>
                <td><?= e($b['brand_name']) ?></td>
                <td><a class="btn" href="brand.php?edit_id=<?= (int)$b['brand_id'] ?>">Edit</a></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

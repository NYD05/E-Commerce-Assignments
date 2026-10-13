<?php
// views/admin/category.php — add + edit categories. require_admin() BEFORE any HTML.
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';
$controller = new ProductController();

$editCat = null;
$editId = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($editId) {
    $editCat = $controller->getCategoryById($editId);
    if (!$editCat) {
        $_SESSION['error'] = 'Category not found.';
    }
}

$categories = $controller->getAllCategories();

$success = $_SESSION['success'] ?? null;
$error   = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

require_once __DIR__ . '/../layout/header.php';
?>
<main style="padding: 30px;">
    <h1><?= $editCat ? 'Edit Category' : 'Categories' ?></h1>

    <?php if ($success): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="error"><?= e($error) ?></div><?php endif; ?>

    <?php if ($editCat): ?>
        <form method="POST" action="../../actions/update_category_action.php">
            <input type="hidden" name="cat_id" value="<?= e($editCat['cat_id']) ?>">
            <input type="text" name="cat_name" value="<?= e($editCat['cat_name']) ?>" maxlength="100" required>
            <button type="submit">Update Category</button>
            <a href="category.php">Cancel</a>
        </form>
    <?php else: ?>
        <form method="POST" action="../../actions/add_category_action.php">
            <input type="text" name="cat_name" placeholder="Category name" maxlength="100" required>
            <button type="submit">Add Category</button>
        </form>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 20px;">
        <thead><tr><th>#</th><th>Category</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($categories)): ?>
            <tr><td colspan="3">No categories yet.</td></tr>
        <?php else: foreach ($categories as $c): ?>
            <tr>
                <td><?= e($c['cat_id']) ?></td>
                <td><?= e($c['cat_name']) ?></td>
                <td><a class="btn" href="category.php?edit_id=<?= (int)$c['cat_id'] ?>">Edit</a></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

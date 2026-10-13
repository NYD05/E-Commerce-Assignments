<?php
// actions/product_image_upload.php — shared by add_product_action.php and update_product_action.php.
// Server-side checks: the JavaScript checks are only for convenience; THESE are the real security.

const PRODUCT_IMAGE_MAX_BYTES = 2 * 1024 * 1024;   // 2 MB
const PRODUCT_IMAGE_DIR       = __DIR__ . '/../images/products/';

/**
 * Validates and moves $_FILES['product_image'].
 * Returns ['filename' => string|null, 'error' => string|null].
 * filename is null with no error when no file was chosen (allowed on edit).
 */
function handle_product_image_upload(): array
{
    $file = $_FILES['product_image'] ?? null;

    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['filename' => null, 'error' => null];
    }
    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        return ['filename' => null, 'error' => 'Image is too large (max 2 MB).'];
    }
    if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        return ['filename' => null, 'error' => 'Image upload failed. Please try again.'];
    }
    if ($file['size'] > PRODUCT_IMAGE_MAX_BYTES) {
        return ['filename' => null, 'error' => 'Image is too large (max 2 MB).'];
    }

    // 1) Check the type the browser reported (as the assignment shows)...
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed, true)) {
        return ['filename' => null, 'error' => 'Only JPG, PNG, GIF or WEBP images are allowed.'];
    }
    // 2) ...then check the ACTUAL file contents, because the browser-reported type can be faked.
    $realType = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!in_array($realType, $allowed, true) || @getimagesize($file['tmp_name']) === false) {
        return ['filename' => null, 'error' => 'That file is not a valid image.'];
    }

    if (!is_dir(PRODUCT_IMAGE_DIR) && !mkdir(PRODUCT_IMAGE_DIR, 0755, true)) {
        return ['filename' => null, 'error' => 'Image folder is missing and could not be created.'];
    }

    // Unique name + strip odd characters so the name is always safe in a URL/path.
    $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file['name']));
    $filename = uniqid() . '_' . $safeName;

    if (!move_uploaded_file($file['tmp_name'], PRODUCT_IMAGE_DIR . $filename)) {
        return ['filename' => null, 'error' => 'Could not save the image. Check that images/products is writable.'];
    }

    return ['filename' => $filename, 'error' => null];
}

/** Validates the text/number fields shared by add and edit. Returns [data, error]. */
function validate_product_fields(): array
{
    $cat      = filter_input(INPUT_POST, 'product_cat',   FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $brand    = filter_input(INPUT_POST, 'product_brand', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $price    = filter_input(INPUT_POST, 'product_price', FILTER_VALIDATE_FLOAT);
    $title    = trim(strip_tags($_POST['product_title'] ?? ''));
    $desc     = trim(strip_tags($_POST['product_desc'] ?? ''));
    $keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));

    if (!$cat)                                   return [null, 'Please choose a category.'];
    if (!$brand)                                 return [null, 'Please choose a brand.'];
    if ($title === '' || mb_strlen($title) > 200) return [null, 'Product title is required (max 200 characters).'];
    if ($price === false || $price === null || $price <= 0) return [null, 'Please enter a valid price greater than 0.'];

    return [compact('cat', 'brand', 'title', 'price', 'desc', 'keywords'), null];
}

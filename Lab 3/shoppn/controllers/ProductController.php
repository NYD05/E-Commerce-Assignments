<?php
// controllers/ProductController.php — thin layer between Actions/Views and the Model.
require_once __DIR__ . '/../classes/ProductClass.php';

class ProductController
{
    private ProductClass $product;

    public function __construct()
    {
        $this->product = new ProductClass();
    }

    /* Brands */
    public function addBrand(string $name): bool          { return $this->product->addBrand($name); }
    public function getAllBrands(): array                  { return $this->product->getAllBrands(); }
    public function getBrandById(int $id): ?array          { return $this->product->getBrandById($id); }
    public function updateBrand(int $id, string $name): bool { return $this->product->updateBrand($id, $name); }

    /* Categories */
    public function addCategory(string $name): bool          { return $this->product->addCategory($name); }
    public function getAllCategories(): array                 { return $this->product->getAllCategories(); }
    public function getCategoryById(int $id): ?array          { return $this->product->getCategoryById($id); }
    public function updateCategory(int $id, string $name): bool { return $this->product->updateCategory($id, $name); }

    /* Products — add & edit (Task 9).
     * The Action moves the uploaded file; the Controller only receives the filename string. */
    public function addProduct(int $cat, int $brand, string $title, float $price,
                               string $desc, string $image_filename, string $keywords): bool
    {
        return $this->product->addProduct($cat, $brand, $title, $price, $desc, basename($image_filename), $keywords);
    }

    public function updateProduct(int $id, int $cat, int $brand, string $title, float $price,
                                  string $desc, string $image_filename, string $keywords): bool
    {
        return $this->product->updateProduct($id, $cat, $brand, $title, $price, $desc, basename($image_filename), $keywords);
    }

    public function getProductById(int $id): ?array { return $this->product->getProductById($id); }
    public function getAllProducts(): array          { return $this->product->getAllProducts(); }

    /* Products — display & search (Task 10) */
    public function getFeaturedProducts(int $limit = 6): array { return $this->product->getFeaturedProducts($limit); }
    public function getProductsByCategory(int $cat_id): array  { return $this->product->getProductsByCategory($cat_id); }
    public function getProductsByBrand(int $brand_id): array   { return $this->product->getProductsByBrand($brand_id); }
    public function searchProducts(string $query): array       { return $this->product->searchProducts($query); }
}

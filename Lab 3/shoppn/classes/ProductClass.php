<?php
// classes/ProductClass.php — Model: the ONLY place SQL runs for brands/categories.
require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database
{
    /* ---------------- BRANDS (Tasks 5 & 6) ---------------- */

    public function addBrand(string $name): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO brands (brand_name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getAllBrands(): array
    {
        $result = $this->conn->query("SELECT * FROM brands ORDER BY brand_name ASC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getBrandById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function updateBrand(int $id, string $name): bool
    {
        $stmt = $this->conn->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
        $stmt->bind_param("si", $name, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /* ---------------- CATEGORIES (Tasks 7 & 8) ---------------- */

    public function addCategory(string $name): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getAllCategories(): array
    {
        $result = $this->conn->query("SELECT * FROM categories ORDER BY cat_name ASC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCategoryById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE cat_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function updateCategory(int $id, string $name): bool
    {
        $stmt = $this->conn->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ?");
        $stmt->bind_param("si", $name, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /* ---------------- PRODUCTS: ADD & EDIT (Task 9) ---------------- */

    // Shared SELECT: product fields + category name + brand name.
    private const PRODUCT_SELECT =
        "SELECT p.*, c.cat_name, b.brand_name
           FROM products p
           LEFT JOIN categories c ON p.product_cat = c.cat_id
           LEFT JOIN brands b     ON p.product_brand = b.brand_id";

    public function addProduct(int $cat, int $brand, string $title, float $price,
                               string $desc, string $image_filename, string $keywords): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO products
                (product_cat, product_brand, product_title, product_price,
                 product_desc, product_image, product_keywords)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("iisdsss", $cat, $brand, $title, $price, $desc, $image_filename, $keywords);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function updateProduct(int $id, int $cat, int $brand, string $title, float $price,
                                  string $desc, string $image_filename, string $keywords): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE products
                SET product_cat = ?, product_brand = ?, product_title = ?, product_price = ?,
                    product_desc = ?, product_image = ?, product_keywords = ?
              WHERE product_id = ?"
        );
        $stmt->bind_param("iisdsssi", $cat, $brand, $title, $price, $desc, $image_filename, $keywords, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getProductById(int $id): ?array
    {
        $stmt = $this->conn->prepare(self::PRODUCT_SELECT . " WHERE p.product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    // Used by the admin product list (so each product has an Edit button).
    public function getAllProducts(): array
    {
        $result = $this->conn->query(self::PRODUCT_SELECT . " ORDER BY p.product_id DESC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /* ---------------- PRODUCTS: DISPLAY & SEARCH (Task 10) ---------------- */
    // Every method returns an array — empty array when nothing matches, never false.

    public function getFeaturedProducts(int $limit = 6): array
    {
        $stmt = $this->conn->prepare(self::PRODUCT_SELECT . " ORDER BY RAND() LIMIT ?");
        $stmt->bind_param("i", $limit);
        return $this->fetchAll($stmt);
    }

    public function getProductsByCategory(int $cat_id): array
    {
        $stmt = $this->conn->prepare(self::PRODUCT_SELECT . " WHERE p.product_cat = ?");
        $stmt->bind_param("i", $cat_id);
        return $this->fetchAll($stmt);
    }

    public function getProductsByBrand(int $brand_id): array
    {
        $stmt = $this->conn->prepare(self::PRODUCT_SELECT . " WHERE p.product_brand = ?");
        $stmt->bind_param("i", $brand_id);
        return $this->fetchAll($stmt);
    }

    public function searchProducts(string $query): array
    {
        $like = '%' . $query . '%';
        $stmt = $this->conn->prepare(
            self::PRODUCT_SELECT . " WHERE p.product_title LIKE ? OR p.product_keywords LIKE ?"
        );
        $stmt->bind_param("ss", $like, $like);
        return $this->fetchAll($stmt);
    }

    private function fetchAll(mysqli_stmt $stmt): array
    {
        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows ?: [];
    }
}

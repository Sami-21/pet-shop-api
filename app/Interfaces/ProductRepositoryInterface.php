<?php

namespace App\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function getProducts(int $limit, string $sortBy, bool $descFilter, string $category, int $price, string $brandTitle, string $title);

    public function getProduct(string $uuid);

    public function storeProduct(array $data);

    public function updateProduct(Product $product, array $data);

    public function deleteProduct(Product $product);
}

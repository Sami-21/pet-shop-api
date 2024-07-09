<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $ProductRepositoryInstance)
    {
        $this->productRepository = $ProductRepositoryInstance;
    }

    public function getProducts(int $limit, string $sortBy, bool $descFilter, string $category, int $price, string $brandTitle, string $title)
    {
        return $this->productRepository->getProducts($limit, $sortBy, $descFilter, $category, $price, $brandTitle, $title);
    }

    public function getProduct(string $uuid)
    {
        return $this->productRepository->getProduct($uuid);
    }

    public function storeProduct(array $data)
    {
        return $this->productRepository->storeProduct($data);
    }

    public function updateProduct(string $uuid, array $data)
    {
        $product = $this->productRepository->getProduct($uuid);

        return $this->productRepository->updateProduct($product, $data);
    }

    public function deleteProduct(string $uuid)
    {
        $product = $this->getProduct($uuid);
        $this->productRepository->deleteProduct($product);
    }
}

<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $CategoryRepositoryInstance)
    {
        $this->categoryRepository = $CategoryRepositoryInstance;
    }

    public function getCategories(int $limit, string $sortBy, bool $descFilter)
    {
        return $this->categoryRepository->getCategories($limit, $sortBy, $descFilter);
    }

    public function getCategory(string $uuid)
    {
        return $this->categoryRepository->getCategory($uuid);
    }

    public function storeCategory(array $data)
    {
        return $this->categoryRepository->storeCategory($data);
    }

    public function updateCategory(string $uuid, array $data)
    {
        $category = $this->categoryRepository->getCategory($uuid);
        return $this->categoryRepository->updateCategory($category, $data);
    }

    public function deleteCategory(string $uuid)
    {
        $category = $this->getCategory($uuid);
        $this->categoryRepository->deleteCategory($category);
    }
}

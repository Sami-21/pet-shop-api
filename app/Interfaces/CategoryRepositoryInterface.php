<?php

namespace App\Interfaces;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function getCategories(int $limit, string $sortBy, bool $descFilter);

    public function getCategory(string $uuid);

    public function storeCategory(array $data);

    public function updateCategory(Category $category, array $data);

    public function deleteCategory(Category $category);
}

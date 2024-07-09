<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Str;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(int $limit, string $sortBy, bool $descFilter)
    {
        return Category::orderBy($sortBy, $descFilter ? 'desc' : 'asc')->paginate($limit);
    }

    public function getCategory(string $uuid)
    {
        return Category::where('uuid', $uuid)->firstOrFail();
    }

    public function storeCategory(array $data)
    {
        return Category::create(array_merge($data, ['uuid' => Str::uuid()]));
    }

    public function updateCategory(Category $category, array $data)
    {
        $category->update($data);

        return $category;
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
    }
}

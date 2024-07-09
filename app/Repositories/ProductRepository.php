<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Brand;
use App\Models\Product;
use Str;

class ProductRepository implements ProductRepositoryInterface
{
  public function getProducts(int $limit, string $sortBy, bool $descFilter, string $category, int $price, string $brandTitle, string $title)
  {
    $query = Product::query();

    if ($category) {
      $query->whereHas('category', function ($query) use ($category) {
        $query->where('title', 'LIKE', '%' . $category . '%');
      });
    }

    if ($title) {
      $query->where('title', 'LIKE', '%' . $title . '%');
    }

    if ($price) {
      $query->where('price', $price);
    }

    if ($brandTitle) {
      $brand = Brand::where('title', $brandTitle)->first();
      if ($brand) {
        $query->whereJsonContains('metadata->brand_uuid', $brand->uuid);
      }
    }
    $query->orderBy($sortBy, $descFilter ? 'desc' : 'asc');

    return $query->paginate($limit);
  }

  public function getProduct(string $uuid)
  {
    return Product::where('uuid', $uuid)->firstOrFail();
  }

  public function storeProduct(array $data)
  {
    return Product::create(array_merge($data, ['uuid' => Str::uuid(), 'metadata' => json_encode(['brand' => $data['brand'], 'file' => $data['image']])]));
  }

  public function updateProduct(Product $product, array $data)
  {
    $product->update([
      'title' => $data['title'],
      'category_uuid' => $data['category_uuid'],
      'price' => $data['price'],
      'description' => $data['description'],
      'metadata' => json_encode(['brand' => $data['brand'], 'file' => $data['image']])
    ]);
    return $product;
  }

  public function deleteProduct(Product $product)
  {
    $product->delete();
  }
}

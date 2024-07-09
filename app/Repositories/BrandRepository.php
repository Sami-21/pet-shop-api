<?php

namespace App\Repositories;

use App\Interfaces\BrandRepositoryInterface;
use App\Models\Brand;
use Str;

class BrandRepository implements BrandRepositoryInterface
{
    public function getBrands(int $limit, string $sortBy, bool $descFilter)
    {
        return Brand::orderBy($sortBy,  $descFilter ? 'desc' : 'asc')->paginate($limit);
    }

    public function getBrand(string $uuid)
    {
        return Brand::where('uuid', $uuid)->firstOrFail();
    }

    public function storeBrand(array $data)
    {
        return Brand::create(array_merge($data, ['uuid' => Str::uuid()]));
    }

    public function updateBrand(Brand $brand, array $data)
    {
        $brand->update($data);
        return $brand;
    }

    public function deleteBrand(Brand $brand)
    {
        $brand->delete();
    }
}

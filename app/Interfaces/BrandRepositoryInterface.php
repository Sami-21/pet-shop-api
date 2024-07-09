<?php

namespace App\Interfaces;

use App\Models\Brand;

interface BrandRepositoryInterface
{
    public function getBrands(int $limit, string $sortBy, bool $descFilter);

    public function getBrand(string $uuid);

    public function storeBrand(array $data);

    public function updateBrand(Brand $brand, array $data);

    public function deleteBrand(Brand $brand);
}

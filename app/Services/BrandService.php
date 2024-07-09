<?php

namespace App\Services;

use App\Repositories\BrandRepository;

class BrandService
{
    protected BrandRepository $brandRepository;

    public function __construct(BrandRepository $BrandRepositoryInstance)
    {
        $this->brandRepository = $BrandRepositoryInstance;
    }

    public function getBrands(int $limit, string $sortBy, bool $descFilter)
    {
        return $this->brandRepository->getBrands($limit, $sortBy, $descFilter);
    }

    public function getBrand(string $uuid)
    {
        return $this->brandRepository->getBrand($uuid);
    }

    public function storeBrand(array $data)
    {
        return $this->brandRepository->storeBrand($data);
    }

    public function updateBrand(string $uuid, array $data)
    {
        $brand = $this->brandRepository->getBrand($uuid);
        return $this->brandRepository->updateBrand($brand, $data);
    }

    public function deleteBrand(string $uuid)
    {
        $brand = $this->getBrand($uuid);
        $this->brandRepository->deleteBrand($brand);
    }
}

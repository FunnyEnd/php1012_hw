<?php

namespace App\Repository;

use App\Extensions\CategoryNotExistExtension;
use App\Extensions\ProductNotExistExtension;
use App\Models\Product;
use DateTime;
use Framework\BaseRepository;

class ProductRepository extends BaseRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "select * from products where id = ?";
    private const SELECT_ALL = /** @lang text */
            "select * from products";
    private const SELECT_BY_CATEGORY_ID = /** @lang text */
            "select * from products where category_id = ?";

    private $categoryRepository;
    private $imageRepository;

    public function __construct(CategoryRepository $categoryRepository, ImageRepository $imageRepository)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param int $id
     * @return Product
     * @throws ProductNotExistExtension
     */
    public function findById(int $id): Product
    {
        $result = $this->db->getOne(self::SELECT_BY_ID, [$id]);
        if (empty($result))
            throw new ProductNotExistExtension();

        return $this->mapArrayToProduct($result);
    }

    /**
     * Find all products
     * @return array
     */
    public function findAll(): array
    {
        $result = $this->db->getAll(self::SELECT_ALL, []);
        $products = [];
        foreach ($result as $r)
            array_push($products, $this->mapArrayToProduct($r));

        return $products;
    }

    /**
     * Find products by category id
     * @param int $category_id
     * @return array
     */
    public function findByCategoryId(int $category_id): array
    {
        $result = $this->db->getAll(self::SELECT_BY_CATEGORY_ID, [$category_id]);
        $products = [];
        foreach ($result as $r)
            array_push($products, $this->mapArrayToProduct($r));

        return $products;
    }

    /**
     * Convert array to Product object
     * @param array $row
     * @return Product
     */
    private function mapArrayToProduct(array $row): Product
    {
        $product = new Product();
        $row['create_at'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['update_at']);
        try {
            $row['category'] = $this->categoryRepository->findById($row['category_id']);
        } catch (CategoryNotExistExtension $e) {
            die($e->getMessage());
        }
        $row['image'] = $this->imageRepository->findById($row['image_id']);
        $product->formArray($row);
        return $product;
    }
}

<?php

namespace App\Repository;

use App\Extensions\CategoryNotExistExtension;
use App\Extensions\ImageNotExistExtension;
use App\Extensions\ProductNotExistExtension;
use App\Models\Product;
use DateTime;
use Framework\BaseRepository;
use Framework\Constants;

class ProductRepository extends BaseRepository
{
    private const SELECT_BY_ID_SQL = /** @lang text */
            "select * from products where id = :id";
    private const SELECT_ALL_SQL = /** @lang text */
            "select * from products";
    private const SELECT_BY_CATEGORY_ID_SQL = /** @lang text */
            "select * from products where category_id = :category_id";
    private const UPDATE_BY_ID_SQL = /** @lang text */
            "update products set title = :title, description = :description, availability = :availability, " .
            " category_id = :category_id, price = :price, image_id = :image_id, " .
            " update_at = :update_at where id = :id;";
    private const INSERT_SQL = /** @lang text */
            "insert into products (title, description, availability, category_id, price, " .
            " image_id, create_at, update_at) values (:title, :description, :availability, " .
            " :category_id, :price, :image_id, :create_at, :update_at)";
    private const DELETE_BY_ID_SQL = /** @lang text */
            "delete from products where id = :id";


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
        $result = $this->db->getOne(self::SELECT_BY_ID_SQL, ["id" => $id]);
        if (empty($result))
            throw new ProductNotExistExtension("Ohhh, product with id {$id} don`t exist.");

        return $this->mapArrayToProduct($result);
    }

    /**
     * Find all products
     * @return array
     */
    public function findAll(): array
    {
        $result = $this->db->getAll(self::SELECT_ALL_SQL, []);
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
        $result = $this->db->getAll(self::SELECT_BY_CATEGORY_ID_SQL, ["category_id" => $category_id]);
        $products = [];
        foreach ($result as $r)
            array_push($products, $this->mapArrayToProduct($r));

        return $products;
    }

    public function save(Product $product): Product
    {
        $currentDateTime = new DateTime();
        $this->db->execute(self::INSERT_SQL, [
                "title" => $product->getTitle(),
                "description" => $product->getDescription(),
                "availability" => $product->getAvailability(),
                "category_id" => $product->getCategory()->getId(),
                "price" => $product->getPrice(),
                "image_id" => $product->getImage()->getId(),
                "create_at" => $currentDateTime->format(Constants::DATETIME_FORMAT),
                "update_at" => $currentDateTime->format(Constants::DATETIME_FORMAT),
        ]);

        $product->setCreateAt($currentDateTime);
        $product->setUpdateAt($currentDateTime);
        $product->setId($this->db->insertId());

        return $product;
    }

    public function update(Product $product): Product
    {
        $currentDateTime = new DateTime();
        $this->db->execute(self::UPDATE_BY_ID_SQL, [
                "title" => $product->getTitle(),
                "description" => $product->getDescription(),
                "availability" => $product->getAvailability(),
                "category_id" => $product->getCategory()->getId(),
                "price" => $product->getPrice(),
                "image_id" => $product->getImage()->getId(),
                "update_at" => $currentDateTime->format(Constants::DATETIME_FORMAT),
                "id" => $product->getId()
        ]);

        $product->setUpdateAt($currentDateTime);

        return $product;
    }

    public function delete(Product $product): void
    {
        $this->db->execute(self::DELETE_BY_ID_SQL, [
                "id" => $product->getId()
        ]);
    }

    /**
     * Convert array to Product object
     * @todo log error messages
     * @param array $row
     * @return Product
     */
    private function mapArrayToProduct(array $row): Product
    {
        $product = new Product();
        $row['create_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['update_at']);
        try {
            $row['category'] = $this->categoryRepository->findById($row['category_id']);
            $row['image'] = $this->imageRepository->findById($row['image_id']);
        } catch (CategoryNotExistExtension $e) {
            die($e->getMessage());
        } catch (ImageNotExistExtension $e) {
            die($e->getMessage());
        }

        $product->formArray($row);
        return $product;
    }
}

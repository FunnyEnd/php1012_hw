<?php

namespace App\Repository;

use App\Extensions\ProductNotExistExtension;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use DateTime;
use Exception;
use Framework\BaseRepository;
use Framework\Constants;
use Framework\Dispatcher;
use Zaine\Log;

class ProductRepository extends BaseRepository
{
    private const SELECT_BY_ID_SQL = /** @lang text */
            "select products.id, products.title, products.description, products.availability, products.create_at, " .
            "products.update_at, products.price, products.category_id, category.title as 'category_title', " .
            "category.create_at as 'category_create_at', category.update_at as 'category_update_at', " .
            "products.image_id, images.path as 'image_path', images.alt as 'image_alt', images.create_at as 'image_create_at', " .
            "images.update_at as 'image_update_at' " .
            "from products " .
            "left join images on images.id = products.image_id " .
            "left join category on category.id = products.category_id " .
            "where products.id = :id";

    private const SELECT_ALL_SQL = /** @lang text */
            "select products.id, products.title, products.description, products.availability, products.create_at, " .
            "products.update_at, products.price, products.category_id, category.title as 'category_title', " .
            "category.create_at as 'category_create_at', category.update_at as 'category_update_at', " .
            "products.image_id, images.path as 'image_path', images.alt as 'image_alt', images.create_at as 'image_create_at', " .
            "images.update_at as 'image_update_at' " .
            "from products " .
            "left join images on images.id = products.image_id " .
            "left join category on category.id = products.category_id";

    private const SELECT_BY_CATEGORY_ID_SQL = /** @lang text */
            "select products.id, products.title, products.description, products.availability, products.create_at, " .
            "products.update_at, products.price, products.category_id, category.title as 'category_title', " .
            "category.create_at as 'category_create_at', category.update_at as 'category_update_at', " .
            "products.image_id, images.path as 'image_path', images.alt as 'image_alt', images.create_at as 'image_create_at', " .
            "images.update_at as 'image_update_at' " .
            "from products " .
            "left join images on images.id = products.image_id " .
            "left join category on category.id = products.category_id " .
            "where products.category_id = :category_id";

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
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                    'title' => $product->getTitle(),
                    'description' => $product->getDescription(),
                    'availability' => $product->getAvailability(),
                    'category_id' => $product->getCategory()->getId(),
                    'price' => $product->getPriceAtCoins(),
                    'image_id' => $product->getImage()->getId(),
                    'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                    'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $product->setCreateAt($currentDateTime);
            $product->setUpdateAt($currentDateTime);
            $product->setId($this->db->insertId());
            return $product;

        } catch (Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new Product();
    }

    public function update(Product $product): Product
    {
        try {
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

        } catch (Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new Product();
    }

    public function delete(Product $product): void
    {
        $this->db->execute(self::DELETE_BY_ID_SQL, [
                "id" => $product->getId()
        ]);
    }

    private function mapArrayToProduct(array $row): Product
    {
        $category = new Category();
        $category->setId($row['category_id']);
        $category->setTitle($row['category_title']);
        $category->setCreateAt(DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['category_create_at']));
        $category->setUpdateAt(DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['category_update_at']));
        $row['category'] = $category;

        $image = new Image();
        $image->setId($row['image_id']);
        $image->setPath($row['image_path']);
        $image->setAlt($row['image_alt']);
        $image->setCreateAt(DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['image_create_at']));
        $image->setUpdateAt(DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['image_create_at']));
        $row['image'] = $image;

        $row['create_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['update_at']);

        $product = new Product();
        $product->fromArray($row);
        return $product;
    }
}

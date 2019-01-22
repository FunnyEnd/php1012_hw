<?php

namespace App\Repository;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use DateTime;
use Exception;
use Framework\AbstractModel;
use Framework\AbstractRepository;
use Framework\Constants;

class ProductRepository extends AbstractRepository
{
    protected const MODEL_CLASS = Product::class;

    protected const SELECT_ALL_SQL = /** @lang text */
            "select products.id, products.title, products.description, products.availability, products.create_at, " .
            "products.update_at, products.price, products.category_id, category.title as 'category_title', " .
            "category.create_at as 'category_create_at', category.update_at as 'category_update_at', " .
            "products.image_id, images.path as 'image_path', images.alt as 'image_alt', images.create_at as 'image_create_at', " .
            "images.update_at as 'image_update_at' " .
            "from products " .
            "left join images on images.id = products.image_id " .
            "left join category on category.id = products.category_id";

    protected const SELECT_COUNT_SQL = /** @lang text */
            "select count(products.id) as 'count' " .
            "from products";

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

    public function findById(int $id): AbstractModel
    {
        return parent::findOne('products.id = :id', [
                'id' => $id
        ]);
    }

    public function findByCategoryIdWithLimit(int $categoryId, int $from, int $count): array
    {
        return parent::findAll(' products.category_id = :category_id', [
                'category_id' => $categoryId,
                'from' => $from,
                'count' => $count
        ]);
    }

    public function findByCategoryId(int $categoryId): array
    {
        return parent::findAll(' products.category_id = :category_id', [
                'category_id' => $categoryId,
        ]);
    }

    public function findBySearchStringWithLimit(string $searchString, int $from, int $count): array
    {
        $where = '(products.title like :search_string_title) OR ' .
                ' (products.description like :search_string_description)';

        return parent::findAll($where, [
                'search_string_title' => "%$searchString%",
                'search_string_description' => "%$searchString%",
                'from' => $from,
                'count' => $count
        ]);
    }

    public function findCountBySearchString(string $searchString): int
    {
        return parent::findCount('(title like ?) OR (description like ?)', [
                "%$searchString%",
                "%$searchString%"
        ]);
    }

    public function findCountByCategoryId(int $categoryId): int
    {
        return parent::findCount('category_id = :category_id', [
                'category_id' => $categoryId
        ]);
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
            $this->logException($e);
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
                    "price" => $product->getPriceAtCoins(),
                    "image_id" => $product->getImage()->getId(),
                    "update_at" => $currentDateTime->format(Constants::DATETIME_FORMAT),
                    "id" => $product->getId()
            ]);
            $product->setUpdateAt($currentDateTime);
            return $product;

        } catch (Exception $e) {
            $this->logException($e);
        }

        return new Product();
    }

    public function delete(Product $product): void
    {
        $this->db->execute(self::DELETE_BY_ID_SQL, [
                "id" => $product->getId()
        ]);
    }

    protected function mapFromArray(array $row): AbstractModel
    {
        $row['category'] = (new Category())
                ->setId($row['category_id'])
                ->setTitle($row['category_title']);

        $row['image'] = (new Image())
                ->setId($row['image_id'])
                ->setPath($row['image_path'])
                ->setAlt($row['image_alt']);

        return parent::mapFromArray($row);
    }
}

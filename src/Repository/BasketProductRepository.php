<?php

namespace App\Repository;


use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use DateTime;
use Exception;
use Framework\BaseRepository;
use Framework\Constants;
use Framework\Dispatcher;
use Zaine\Log;

class BasketProductRepository extends BaseRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "select baskets_products.id, baskets_products.basket_id, baskets.user_id, baskets_products.count, " .
            "baskets_products.product_id, baskets_products.create_at, baskets_products.update_at, " .
            "products.title as 'product_title', products.price as 'product_price', " .
            "images.path as 'product_image_path', images.alt as 'product_image_alt', products.image_id as 'product_image_id' " .
            "FROM baskets_products " .
            "left join baskets on baskets.id = baskets_products.basket_id " .
            "left join products on products.id = baskets_products.product_id " .
            "left join images on images.id = products.image_id " .
            "where baskets_products.id = :id";

    private const SELECT_BY_USER_ID = /** @lang text */
            "select baskets_products.id, baskets_products.basket_id, baskets.user_id, baskets_products.count, " .
            "baskets_products.product_id, baskets_products.create_at, baskets_products.update_at, " .
            "products.title as 'product_title', products.price as 'product_price', " .
            "images.path as 'product_image_path', images.alt as 'product_image_alt', products.image_id as 'product_image_id' " .
            "FROM baskets_products " .
            "left join baskets on baskets.id = baskets_products.basket_id " .
            "left join products on products.id = baskets_products.product_id " .
            "left join images on images.id = products.image_id " .
            "where baskets.user_id = :user_id";

    private const SELECT_COUNT_BY_PRODUCT_ID_AND_BASKET_ID = /** @lang text */
            "SELECT count(id) as 'count' " .
            "FROM baskets_products " .
            "where product_id = :product_id AND basket_id = :basket_id";

    private const SELECT_BY_PRODUCT_ID_AND_BASKET_ID = /** @lang text */
            "select baskets_products.id, baskets_products.basket_id, baskets.user_id, baskets_products.count, " .
            "baskets_products.product_id, baskets_products.create_at, baskets_products.update_at, " .
            "products.title as 'product_title', products.price as 'product_price', " .
            "images.path as 'product_image_path', images.alt as 'product_image_alt', products.image_id as 'product_image_id' " .
            "FROM baskets_products " .
            "left join baskets on baskets.id = baskets_products.basket_id " .
            "left join products on products.id = baskets_products.product_id " .
            "left join images on images.id = products.image_id " .
            "where baskets_products.basket_id = :basket_id AND baskets_products.product_id = :product_id";
    private const SELECT_COUNT_BY_USER_ID = /** @lang text */
            "select count(id) as 'count' " .
            "from baskets_products " .
            "where baskets_products.basket_id = (select id from baskets " .
            "where user_id = :user_id)";

    private const INSERT_SQL = /** @lang text */
            "insert into baskets_products (basket_id, product_id, count, create_at, update_at) values (:basket_id, :product_id, :count, " .
            ":create_at, :update_at)";

    private const UPDATE_SQL = /** @lang text */
            "update baskets_products " .
            "set `count` = :count, `update_at` = :update_at " .
            "where product_id = :product_id AND basket_id = :basket_id";

    private const DELETE_BY_ID_SQL = /** @lang text */
            "delete from baskets_products where id = :id";

    private const DELETE_BY_BASKET_ID_SQL = /** @lang text */
            "delete from baskets_products where basket_id = :basket_id";

    public function findById(int $id): BasketProduct
    {
        $result = $this->db->getOne(self::SELECT_BY_ID, ['id' => $id]);

        if (empty($result)) {
            return null;
        }

        return $this->mapArrayToBasketProduct($result);
    }

    /**
     * Convert array to Image object
     * @param array $row
     * @return BasketProduct
     */
    private function mapArrayToBasketProduct(array $row): BasketProduct
    {
        $basket = new Basket();
        $basket->setId($row['basket_id']);

        $user = new User();
        $user->setId($row['user_id']);

        $basket->setUser($user);
        $row['basket'] = $basket;

        $product = new Product();
        $product->setId($row['product_id']);
        $product->setTitle($row['product_title']);
        $product->setPriceAtCoins($row['product_price']);

        $image = new Image();
        $image->setId($row['product_image_id']);
        $image->setPath($row['product_image_path']);
        $image->setAlt($row['product_image_alt']);

        $product->setImage($image);
        $row['product'] = $product;

        $row['create_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['update_at']);

        $basketProduct = new BasketProduct();
        $basketProduct->fromArray($row);
        return $basketProduct;
    }

    public function findByUserId(int $userId): array
    {
        $result = $this->db->getAll(self::SELECT_BY_USER_ID, ['user_id' => $userId]);
        $basketProduct = [];
        foreach ($result as $r)
            array_push($basketProduct, $this->mapArrayToBasketProduct($r));

        return $basketProduct;
    }

    /**
     * @param int $productId
     * @param int $basketId
     * @return BasketProduct
     */
    public function findByProductIdAndBasketId(int $productId, int $basketId): BasketProduct
    {
        $result = $this->db->getOne(self::SELECT_BY_PRODUCT_ID_AND_BASKET_ID, [
                        'product_id' => $productId,
                        'basket_id' => $basketId]
        );

        if (empty($result)) {
            return null;
        }

        return $this->mapArrayToBasketProduct($result);
    }

    public function save(BasketProduct $basketProduct): BasketProduct
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                    'basket_id' => $basketProduct->getBasket()->getId(),
                    'product_id' => $basketProduct->getProduct()->getId(),
                    'count' => $basketProduct->getCount(),
                    'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                    'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $basketProduct->setCreateAt($currentDateTime);
            $basketProduct->setUpdateAt($currentDateTime);
            $basketProduct->setId($this->db->insertId());
            return $basketProduct;

        } catch (Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new BasketProduct();
    }

    public function update(BasketProduct $basketProduct): BasketProduct
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::UPDATE_SQL, [
                    'basket_id' => $basketProduct->getBasket()->getId(),
                    'product_id' => $basketProduct->getProduct()->getId(),
                    'count' => $basketProduct->getCount(),
                    'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $basketProduct->setUpdateAt($currentDateTime);
            return $basketProduct;

        } catch (Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new BasketProduct();
    }

    public function isProductExist(BasketProduct $basketProduct): bool
    {
        $result = $this->db->getOne(self::SELECT_COUNT_BY_PRODUCT_ID_AND_BASKET_ID, [
                'product_id' => $basketProduct->getProduct()->getId(),
                'basket_id' => $basketProduct->getBasket()->getId()
        ]);

        return (intval($result['count']) !== 0);
    }

    public function getCountProductsAtUserBasket(User $user): int
    {
        $result = $this->db->getOne(self::SELECT_COUNT_BY_USER_ID, [
                'user_id' => $user->getId(),
        ]);

        return intval($result['count']);
    }

    public function delete(BasketProduct $basketProduct)
    {
        $this->db->execute(self::DELETE_BY_ID_SQL, [
                "id" => $basketProduct->getId()
        ]);
    }

    public function deleteByBasketId(int $basketId)
    {
        $this->db->execute(self::DELETE_BY_BASKET_ID_SQL, [
                "basket_id" => $basketId
        ]);
    }
}

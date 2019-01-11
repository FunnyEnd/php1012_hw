<?php

namespace App\Repository;


use App\Extensions\BasketProductNotExistExtension;
use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use DateTime;
use Framework\BaseRepository;
use Framework\Constants;

class BasketProductRepository extends BaseRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "select baskets_products.id, baskets_products.basket_id, baskets.user_id, baskets_products.count, ".
            "baskets_products.product_id, baskets_products.create_at, baskets_products.update_at, ".
            "products.title as 'product_title', products.price as 'product_price', ".
            "images.path as 'product_image_path', images.alt as 'product_image_alt', products.image_id as 'product_image_id' ".
            "FROM baskets_products ".
            "left join baskets on baskets.id = baskets_products.basket_id ".
            "left join products on products.id = baskets_products.product_id ".
            "left join images on images.id = products.image_id ".
            "where baskets_products.id = :id";

    /**
     * @param int $id
     * @return BasketProduct
     * @throws BasketProductNotExistExtension
     */
    public function findById(int $id): BasketProduct
    {
        $result = $this->db->getOne(self::SELECT_BY_ID, ['id' => $id]);
        if (empty($result))
            throw new BasketProductNotExistExtension();

        return $this->mapArrayToBasketProduct($result);
    }

    public function findByUserId(int $userId)
    {
    }

    public function save(BasketProduct $basketProduct)
    {
    }

    public function update(BasketProduct $basketProduct)
    {
    }

    public function delete(BasketProduct $basketProduct)
    {
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
}
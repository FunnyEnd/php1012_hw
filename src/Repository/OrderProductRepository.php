<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use DateTime;
use Exception;
use Framework\AbstractModel;
use Framework\AbstractRepository;
use Framework\Constants;
use Framework\Dispatcher;
use ReflectionClass;
use Zaine\Log;

class OrderProductRepository extends AbstractRepository
{
    protected const MODEL_CLASS = OrderProduct::class;

    protected const SELECT_ALL_SQL = /** @lang text */
        'SELECT orders_products.order_id, orders_products.product_id, orders_products.price, orders_products.count, ' .
        'orders_products.create_at, orders_products.update_at, orders_products.id, products.title as \'product_title\' ' .
        'FROM orders_products ' .
        'left join products on products.id = orders_products.product_id';

    protected const SELECT_SUM_PRICE_BY_ORDER_ID = /** @lang text */
        '';

    protected const INSERT_SQL = /** @lang text */
        "insert into orders_products (order_id, product_id, price, count, create_at, update_at) values " .
        "(:order_id, :product_id, :price, :count, :create_at, :update_at)";


    public function save(AbstractModel $orderProduct): AbstractModel
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                'order_id' => $orderProduct->getOrder()->getId(),
                'product_id' => $orderProduct->getProduct()->getId(),
                'count' => $orderProduct->getCount(),
                'price' => $orderProduct->getProduct()->getPriceAtCoins(),
                'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $orderProduct->setCreateAt($currentDateTime);
            $orderProduct->setUpdateAt($currentDateTime);
            $orderProduct->setId($this->db->insertId());
            return $orderProduct;

        } catch (Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new OrderProduct();
    }

    protected function mapFromArray(array $row): AbstractModel
    {
        $row['product'] = (new Product())
            ->setId($row['product_id'])
            ->setTitle($row['product_title']);

        $row['order'] = (new Order())
            ->setId($row['order_id']);

        return parent::mapFromArray($row);
    }
}
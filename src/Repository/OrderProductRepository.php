<?php

namespace App\Repository;

use App\Models\OrderProduct;
use DateTime;
use Exception;
use Framework\BaseRepository;
use Framework\Constants;
use Framework\Dispatcher;
use Zaine\Log;

class OrderProductRepository extends BaseRepository
{
    private const INSERT_SQL = /** @lang text */
            "insert into orders_products (order_id, product_id, price, count, create_at, update_at) values " .
            "(:order_id, :product_id, :price, :count, :create_at, :update_at)";

    public function findById(int $id): OrderProduct
    {
        return new OrderProduct();
    }


    public function save(OrderProduct $orderProduct): OrderProduct
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

    /**
     * Convert array to Image object
     * @param array $row
     * @return OrderProduct
     */
//    private function mapArrayToOrderProduct(array $row): OrderProduct
//    {
//
//        $row['create_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['create_at']);
//        $row['update_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['update_at']);
//
//        $orderProduct = new OrderProduct();
//        $orderProduct->fromArray($row);
//        return $orderProduct;
//    }
}
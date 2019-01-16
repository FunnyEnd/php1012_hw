<?php

namespace App\Repository;

use App\Extensions\BasketNotExistExtension;
use App\Models\Basket;
use App\Models\Order;
use App\Models\User;
use DateTime;
use Framework\BaseModel;
use Framework\BaseRepository;
use Framework\Constants;
use Framework\Dispatcher;
use Exception;
use Zaine\Log;

class OrderRepository extends BaseRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "";

    private const INSERT_SQL = /** @lang text */
            "INSERT INTO `orders` " .
            "(`user_id`, `confirm`, `create_at`, `update_at`," .
            "`comment`, `contact_person_id` ) " .
            "VALUES (:user_id, :confirm, :create_at, :update_at, :comment, " .
            ":contact_person_id);";


    public function findById(int $id): Order
    {
        return new Order();
    }

    public function save(Order $order): Order
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                    'user_id' => $order->getUser()->getId(),
                    'confirm' => $order->getConfirm(),
                    'comment' => $order->getComment(),
                    'contact_person_id' => $order->getContactPerson()->getId(),
                    'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                    'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $order->setCreateAt($currentDateTime);
            $order->setUpdateAt($currentDateTime);
            $order->setId($this->db->insertId());

            return $order;

        } catch (Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new Order();
    }

    // findByUserId

    /**
     * Convert array to Image object
     * @param array $row
     * @return Order
     */
    private function mapArrayToOrder(array $row): Order
    {
        $order = new Order();
        $row['create_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['update_at']);
        $user = new User();
        $user->setId($row['user_id']);
        $row['user'] = $user;
        $order->fromArray($row);
        return $order;
    }
}

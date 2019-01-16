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
            "SELECT baskets.id, baskets.user_id, baskets.create_at, baskets.update_at, " .
            "users.email as 'user_email', users.password as 'user_password', users.first_name as 'user_first_name', " .
            "users.last_name as 'user_last_name', users.is_admin as 'user_is_admin', users.create_at as 'user_create_at', " .
            "users.update_at as 'user_update_at' " .
            "FROM baskets " .
            "left join users on users.id = baskets.user_id " .
            "where id = :id";

    private const INSERT_SQL = /** @lang text */
            "INSERT INTO `orders` " .
            "(`user_id`, `confirm`, `create_at`, `update_at`," .
            "`comment`, `user_stock`, `user_city`, `user_phone`, " .
            "`user_first_name`, `user_last_name`) " .
            "VALUES (:user_id, :confirm, :create_at, :update_at, :comment, " .
            ":user_stock, :user_city, :user_phone, :user_first_name, :user_last_name);";

    /**
     * Find basket by id
     * @param int $id
     * @return Basket
     * @throws BasketNotExistExtension
     */
    public function findById(int $id): BaseModel
    {
        $result = $this->db->getOne(self::SELECT_BY_ID, ['id' => $id]);
        if (empty($result))
            throw new BasketNotExistExtension();

        return $this->mapArrayToBasket($result);
    }

    public function save(Order $order): Order
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                    'user_id' => $order->getUser()->getId(),
                    'confirm' => $order->getConfirm(),
                    'comment' => $order->getComment(),
                    'user_stock' => $order->getUserStock(),
                    'user_city' => $order->getUserCity(),
                    'user_phone' => $order->getUserPhone(),
                    'user_first_name' => $order->getUserFirstName(),
                    'user_last_name' => $order->getUserLastName(),
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
     * @return Basket
     */
    private function mapArrayToBasket(array $row): BaseModel
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

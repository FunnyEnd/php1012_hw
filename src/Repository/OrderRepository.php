<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\User;
use DateTime;
use Exception;
use Framework\AbstractModel;
use Framework\AbstractRepository;
use Framework\Constants;
use Framework\Dispatcher;
use Zaine\Log;

class OrderRepository extends AbstractRepository
{
    protected const MODEL_CLASS = Order::class;

    protected const SELECT_ALL_SQL = /** @lang text */
        'SELECT orders.id, orders.contact_person_id, confirm, comment, user_id, users.email, orders.create_at, ' .
        'orders.update_at FROM orders left join users on users.id = orders.user_id';

    private const INSERT_SQL = /** @lang text */
        "INSERT INTO `orders` " .
        "(`user_id`, `confirm`, `create_at`, `update_at`," .
        "`comment`, `contact_person_id` ) " .
        "VALUES (:user_id, :confirm, :create_at, :update_at, :comment, " .
        ":contact_person_id);";


    public function save(AbstractModel $order): AbstractModel
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                'user_id' => (($order->getUser()->getId()) === 0) ? null : $order->getUser()->getId(),
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

    protected function mapFromArray(array $row): AbstractModel
    {
        $row['user'] = (new User())
            ->setId($row['user_id']);

        return parent::mapFromArray($row);
    }
}

<?php

namespace App\Repository;

use App\Models\ContactPerson;
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
        'SELECT orders.id, orders.contact_person_id, confirm, comment, IFNULL(op.order_price, 0) as \'price\', ' .
        'IFNULL(user_id, 0) as \'user_id\', orders.create_at, orders.update_at, cp.email ' .
        'FROM orders  ' .
        'left join contacts_persons as cp on cp.id = orders.contact_person_id ' .
        'left join ( select order_id, sum((price * count) / 100) as \'order_price\' ' .
        'from orders_products ' .
        'group by orders_products.order_id) as op on op.order_id = orders.id ';

    protected const SELECT_COUNT_SQL = /** @lang text */
        'SELECT count(orders.id) as \'count\' FROM orders';

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

        $row['contact_person'] = (new ContactPerson())
            ->setId($row['contact_person_id'])
            ->setEmail($row['email']);

        return parent::mapFromArray($row);
    }
}

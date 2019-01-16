<?php

namespace App\Repository;

use App\Extensions\BasketNotExistExtension;
use App\Models\Basket;
use App\Models\ContactPerson;
use App\Models\User;
use DateTime;
use Framework\BaseRepository;
use Framework\Constants;
use Framework\Dispatcher;
use Exception;
use Zaine\Log;

class BasketRepository extends BaseRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "SELECT baskets.id, baskets.user_id, baskets.create_at, baskets.update_at, " .
            "users.email as 'user_email', users.password as 'user_password', users.contact_person_id as 'user_contact_person_id', " .
            "users.is_admin as 'user_is_admin', users.create_at as 'user_create_at', " .
            "users.update_at as 'user_update_at' " .
            "FROM baskets " .
            "left join users on users.id = baskets.user_id " .
            "where id = :id";

    private const SELECT_BY_USER_ID = /** @lang text */
            "SELECT baskets.id, baskets.user_id, baskets.create_at, baskets.update_at, " .
            "users.email as 'user_email', users.password as 'user_password', users.contact_person_id as 'user_contact_person_id', " .
            "users.is_admin as 'user_is_admin', users.create_at as 'user_create_at', " .
            "users.update_at as 'user_update_at' " .
            "FROM baskets " .
            "left join users on users.id = baskets.user_id " .
            "where baskets.user_id = :user_id";

    const INSERT_SQL = /** @lang text */
            "insert into baskets (user_id, create_at, update_at) values (:user_id, :create_at, :update_at)";

    private const DELETE_BY_ID_SQL = /** @lang text */
            "delete from baskets where id = :id";

    /**
     * Find basket by id
     * @param int $id
     * @return Basket
     * @throws BasketNotExistExtension
     */
    public function findById(int $id): Basket
    {
        $result = $this->db->getOne(self::SELECT_BY_ID, ['id' => $id]);
        if (empty($result))
            throw new BasketNotExistExtension();

        return $this->mapArrayToBasket($result);
    }

    /**
     * Convert array to Image object
     * @param array $row
     * @return Basket
     */
    private function mapArrayToBasket(array $row): Basket
    {
        $basket = new Basket();
        $row['create_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['update_at']);
        $user = (new User())
                ->setId($row['user_id'])
                ->setContactPerson((new ContactPerson())
                        ->setId($row['user_contact_person_id'])
                );

        $row['user'] = $user;
        $basket->fromArray($row);
        return $basket;
    }

    /**
     * Find basket by user_id
     * @param string $userId
     * @return Basket
     * @throws BasketNotExistExtension
     */
    public function findByUserId(string $userId)
    {
        $result = $this->db->getOne(self::SELECT_BY_USER_ID, ['user_id' => $userId]);
        if (empty($result))
            throw new BasketNotExistExtension();

        return $this->mapArrayToBasket($result);
    }

    public function save(Basket $basket)
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                    'user_id' => $basket->getUser()->getId(),
                    'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                    'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $basket->setCreateAt($currentDateTime);
            $basket->setUpdateAt($currentDateTime);
            $basket->setId($this->db->insertId());
            return $basket;

        } catch (Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new Basket();
    }

    public function delete(Basket $basket)
    {
        $this->db->execute(self::DELETE_BY_ID_SQL, [
                "id" => $basket->getId()
        ]);
    }
}

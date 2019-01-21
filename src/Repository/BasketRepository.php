<?php

namespace App\Repository;

use App\Models\Basket;
use App\Models\ContactPerson;
use App\Models\User;
use DateTime;
use Exception;
use Framework\AbstractModel;
use Framework\AbstractRepository;
use Framework\Constants;

class BasketRepository extends AbstractRepository
{
    protected const MODEL_CLASS = Basket::class;

    protected const SELECT_ALL_SQL = /** @lang text */
            "SELECT baskets.id, baskets.user_id, baskets.create_at, baskets.update_at, " .
            "users.email as 'user_email', users.password as 'user_password', users.contact_person_id as 'user_contact_person_id', " .
            "users.is_admin as 'user_is_admin', users.create_at as 'user_create_at', " .
            "users.update_at as 'user_update_at' " .
            "FROM baskets " .
            "left join users on users.id = baskets.user_id";

    private const INSERT_SQL = /** @lang text */
            "insert into baskets (user_id, create_at, update_at) values (:user_id, :create_at, :update_at)";

    private const DELETE_BY_ID_SQL = /** @lang text */
            "delete from baskets where id = :id";

    /**
     * @param string $userId
     * @return Basket|AbstractModel
     */
    public function findByUserId(string $userId)
    {
        return parent::findOne('baskets.user_id = :user_id', [
                'user_id' => $userId
        ]);
    }

    public function save(AbstractModel $basket): AbstractModel
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
            $this->logException($e);
        }

        return new Basket();
    }

    public function delete(AbstractModel $basket): void
    {
        $this->db->execute(self::DELETE_BY_ID_SQL, [
                "id" => $basket->getId()
        ]);
    }

    protected function mapFromArray(array $row): AbstractModel
    {
        $row['user'] = (new User())
                ->setId($row['user_id'])
                ->setContactPerson(
                        (new ContactPerson())
                                ->setId($row['user_contact_person_id'])
                );

        return parent::mapFromArray($row);
    }
}

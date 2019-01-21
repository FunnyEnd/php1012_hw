<?php

namespace App\Repository;

use App\Models\ContactPerson;
use DateTime;
use Exception;
use Framework\AbstractModel;
use Framework\AbstractRepository;
use Framework\Constants;
use Framework\Dispatcher;
use Zaine\Log;

class ContactPersonRepository extends AbstractRepository
{
    protected const MODEL_CLASS = ContactPerson::class;

    protected const SELECT_BY_ID_SQL = /** @lang text */
            "select * from users where id = :id";

    const INSERT_SQL = /** @lang text */
            "INSERT INTO `contacts_persons` " .
            "(`first_name`, `last_name`, `phone`, `city`, `stock`, `email`, `create_at`, `update_at`) " .
            "VALUES (:first_name, :last_name, :phone, :city, :stock, :email, :create_at, :update_at);";

//    /**
//     * Find user by id
//     * @param int $id
//     * @return ContactPerson
//     * @throws UserNotExistExtension
//     */
//    public function findById(int $id): ContactPerson
//    {
//        $result = $this->db->getOne(self::SELECT_BY_ID, ["id" => $id]);
//        if (empty($result))
//            throw new UserNotExistExtension();
//
//        return $this->mapArrayToContactPerson($result);
//    }

    public function save(AbstractModel $contactPerson): AbstractModel
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                    'first_name' => $contactPerson->getFirstName(),
                    'last_name' => $contactPerson->getLastName(),
                    'phone' => $contactPerson->getPhone(),
                    'city' => $contactPerson->getCity(),
                    'stock' => $contactPerson->getStock(),
                    'email' => $contactPerson->getEmail(),
                    'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                    'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $contactPerson->setCreateAt($currentDateTime);
            $contactPerson->setUpdateAt($currentDateTime);
            $contactPerson->setId($this->db->insertId());
            return $contactPerson;

        } catch (Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new ContactPerson();
    }

    /**
     * Convert array to ContactPerson object
     * @param array $row
     * @return ContactPerson
     */
    protected function mapFromArray(array $row): AbstractModel
    {
        $contactPerson = new ContactPerson();
        $row['create_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['update_at']);
        $contactPerson->fromArray($row);
        return $contactPerson;
    }
}

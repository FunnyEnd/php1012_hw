<?php

namespace App\Repository;

use App\Extensions\UserAlreadyExistExtension;

use App\Models\ContactPerson;
use App\Models\User;
use DateTime;
use Framework\AbstractModel;
use Framework\AbstractRepository;
use Framework\Constants;
use Framework\Dispatcher;
use Zaine\Log;

class UsersRepository extends AbstractRepository
{
    protected const MODEL_CLASS = User::class;

    protected const SELECT_ALL_SQL = /** @lang text */
        "select * from users";

    protected const SELECT_COUNT_SQL = /** @lang text */
        "select count(*) as count from users";

    private const INSERT_SQL = /** @lang text */
        "insert into users (email, password, contact_person_id, is_admin, create_at, update_at) " .
        "values (:email, :password, :contact_person_id, :is_admin, :create_at, :update_at) ";

    public function findByEmail(string $email): AbstractModel
    {
        return $this->findOne('email = :email', [
            "email" => $email
        ]);
    }

    /**
     * @param AbstractModel $user
     * @return User
     * @throws UserAlreadyExistExtension
     */
    public function save(AbstractModel $user): AbstractModel
    {
        $result = $this->findOne('email = :email', [
            "email" => $user->getEmail()
        ]);

        if (!$result->isEmpty()) {
            throw new UserAlreadyExistExtension("User with email `{$user->getEmail()} already exist.`");
        }

        try {
            $currentDateTime = new DateTime();

            $this->db->execute(self::INSERT_SQL, [
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'contact_person_id' => $user->getContactPerson()->getId(),
                'is_admin' => $user->getIsAdmin(),
                'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $user->setCreateAt($currentDateTime);
            $user->setUpdateAt($currentDateTime);
            $user->setId($this->db->insertId());

            return $user;

        } catch (\Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new User();
    }

    protected function mapFromArray(array $row): AbstractModel
    {
        $row['contact_person'] = (new ContactPerson())->setId($row['contact_person_id']);

        return parent::mapFromArray($row);
    }
}

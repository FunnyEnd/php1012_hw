<?php

namespace App\Repository;

use App\Extensions\UserAlreadyExistExtension;
use App\Extensions\UserNotExistExtension;
use App\Models\User;
use DateTime;
use Framework\BaseRepository;

class UsersRepository extends BaseRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "select * from users where id = id";
    private const SELECT_BY_EMAIL = /** @lang text */
            "select * from users where email = email";
    private const UPDATE_BY_ID_SQL = /** @lang text */
            "update products set title = :title, description = :description, availability = :availability, " .
            " category_id = :category_id, price = :price, image_id = :image_id, " .
            " update_at = :update_at where id = :id;";
    private const INSERT_SQL = /** @lang text */
            "insert into users (email, password, first_name, last_name, is_admin, create_at, update_at) " .
            "values (:email, :password, :first_name, :last_name, :is_admin, :create_at, :update_at) ";
    private const DELETE_BY_ID_SQL = /** @lang text */
            "delete from products where id = :id";

    /**
     * Find user by id
     * @param int $id
     * @return User
     * @throws UserNotExistExtension
     */
    public function findById(int $id): User
    {
        $result = $this->db->getOne(self::SELECT_BY_ID, ["id" => $id]);
        if (empty($result))
            throw new UserNotExistExtension();

        return $this->mapArrayToUser($result);
    }

    /**
     * @param string $email
     * @return User
     * @throws UserNotExistExtension
     */
    public function findByEmail(string $email): User
    {
        $result = $this->db->getOne(self::SELECT_BY_EMAIL, ["email" => $email]);
        if (empty($result))
            throw new UserNotExistExtension();

        return $this->mapArrayToUser($result);
    }

    /**
     * @param User $user
     * @return User
     * @throws UserAlreadyExistExtension
     */
    public function save(User $user): User
    {
        $currentDateTime = new DateTime();

        $result = $this->db->getOne(self::SELECT_BY_EMAIL, ["email" => $user->getEmail()]);
        if (!empty($result))
            throw new UserAlreadyExistExtension();

        $this->db->execute(self::INSERT_SQL, [
                "email" => $user->getEmail(),
                "password" => $user->getPassword(),
                "first_name" => $user->getFirstName(),
                "last_name" => $user->getLastName(),
                "is_admin" => $user->getIsAdmin(),
                "create_at" => $currentDateTime->format(self::DATETIME_FORMAT),
                "update_at" => $currentDateTime->format(self::DATETIME_FORMAT),
        ]);

        $user->setCreateAt($currentDateTime);
        $user->setUpdateAt($currentDateTime);
        $user->setId($this->db->insertId());

        return $user;
    }

//    public function update(Product $product): Product
//    {
//        $currentDateTime = new DateTime();
//        $this->db->execute(self::UPDATE_BY_ID_SQL, [
//                "title" => $product->getTitle(),
//                "description" => $product->getDescription(),
//                "availability" => $product->getAvailability(),
//                "category_id" => $product->getCategory()->getId(),
//                "price" => $product->getPrice(),
//                "image_id" => $product->getImage()->getId(),
//                "update_at" => $currentDateTime->format(self::DATETIME_FORMAT),
//                "id" => $product->getId()
//        ]);
//
//        $product->setUpdateAt($currentDateTime);
//
//        return $product;
//    }
//
//    public function delete(Product $product): void
//    {
//        $this->db->execute(self::DELETE_BY_ID_SQL, [
//                "id" => $product->getId()
//        ]);
//    }

    /**
     * Convert array to user object
     * @param array $row
     * @return User
     */
    private function mapArrayToUser(array $row): User
    {
        $user = new User();
        $row['create_at'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['update_at']);
        $user->formArray($row);
        return $user;
    }
}

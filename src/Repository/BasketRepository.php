<?php

namespace App\Repository;

use App\Extensions\BasketNotExistExtension;
use App\Models\Basket;
use DateTime;
use Framework\BaseRepository;
use Framework\Constants;

class BasketRepository extends BaseRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "SELECT baskets.id, baskets.user_id, baskets.create_at, baskets.update_at, " .
            "users.email as 'user_email', users.password as 'user_password', users.first_name as 'user_first_name', " .
            "users.last_name as 'user_last_name', users.is_admin as 'user_is_admin', users.create_at as 'user_create_at', " .
            "users.update_at as 'user_update_at' " .
            "FROM baskets " .
            "left join users on users.id = baskets.user_id " .
            "where id = :id";

    /**
     * Find image by id
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
        $basket->fromArray($row);
        return $basket;
    }
}

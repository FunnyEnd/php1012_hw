<?php

namespace App\Repository;

use App\Models\Category;
use DateTime;
use Framework\BaseRepository;
use Framework\Constants;

class CategoryRepository extends BaseRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "select id, title, create_at, update_at from category where id = :id";

    private const SELECT_ALL = /** @lang text */
            "select id, title, create_at, update_at from category";

    public function findById(int $id): Category
    {
        $result = $this->db->getOne(self::SELECT_BY_ID, ['id' => $id]);

        if (empty($result)) {
            return new Category();
        }

        return $this->mapArrayToCategory($result);
    }
    
    public function findAll(): array
    {
        $result = $this->db->getAll(self::SELECT_ALL, []);
        $products = [];

        foreach ($result as $r) {
            array_push($products, $this->mapArrayToCategory($r));
        }

        return $products;
    }

    private function mapArrayToCategory(array $row): Category
    {
        $category = new Category();
        $row['create_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['update_at']);
        $category->formArray($row);
        return $category;
    }
}

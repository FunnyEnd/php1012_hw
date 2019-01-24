<?php

namespace App\Repository;

use App\Models\Category;
use DateTime;
use Exception;
use Framework\AbstractRepository;
use Framework\Constants;
use Framework\Dispatcher;
use Zaine\Log;

class CategoryRepository extends AbstractRepository
{
    protected const MODEL_CLASS = Category::class;

    protected const SELECT_ALL_SQL = /** @lang text */
        "select id, title, create_at, update_at from category";

    protected const SELECT_COUNT_SQL = /** @lang text */
        'select count(id) as \'count\' from category';

    private const INSERT_SQL = /** @lang text */
        'insert into category (title, create_at, update_at) values (:title, :create_at, :update_at)';

    const UPDATE_BY_ID_SQL = /** @lang text */
        "update category set title = :title, update_at = :update_at where id = :id;";

    private const DELETE_BY_ID_SQL = /** @lang text */
        "delete from category where id = :id";

    public function save(Category $category): Category
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                'title' => $category->getTitle(),
                'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $category->setCreateAt($currentDateTime);
            $category->setUpdateAt($currentDateTime);
            $category->setId($this->db->insertId());

            return $category;

        } catch (Exception $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error($e->getMessage());
            $logger->error($e->getTraceAsString());
        }

        return new Category();
    }

    public function update(Category $category)
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::UPDATE_BY_ID_SQL, [
                "title" => $category->getTitle(),
                "update_at" => $currentDateTime->format(Constants::DATETIME_FORMAT),
                "id" => $category->getId()
            ]);

            $category->setUpdateAt($currentDateTime);
            return $category;

        } catch (Exception $e) {
            $this->logException($e);
        }

        return new Category();
    }

    public function delete(Category $category)
    {
        $this->db->execute(self::DELETE_BY_ID_SQL, [
            "id" => $category->getId()
        ]);
    }
}

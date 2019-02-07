<?php

namespace App\Repository;

use App\Models\Characteristic;
use DateTime;
use Exception;
use Framework\AbstractRepository;
use Framework\Constants;

class CharacteristicRepository extends AbstractRepository
{
    protected const MODEL_CLASS = Characteristic::class;

    protected const SELECT_ALL_SQL = /** @lang MySQL */
        'SELECT id, title, create_at, update_at FROM characteristics';

    protected const SELECT_COUNT_SQL = /** @lang MySQL */
        'SELECT count(id) as count FROM characteristics';


    protected const INSERT_SQL = /** @lang MySQL */
        'INSERT INTO characteristics(title, create_at, update_at) VALUE (:title, :create_at, :update_at)';

    protected const SELECT_CHARACTERISTICS_BY_CATEGORY_ID = /** @lang MySQL */
        'select products_characteristics.characteristic_id as \'id\',  characteristics.title, characteristics.create_at, characteristics.update_at ' .
        'FROM products_characteristics ' .
        'left join characteristics on characteristics.id = products_characteristics.characteristic_id ' .
        'where products_characteristics.product_id in ( ' .
        'select id ' .
        'from products ' .
        'where products.category_id = :category_id) ' .
        'group by products_characteristics.characteristic_id, characteristics.title';

    private const DELETE_BY_ID_SQL = /** @lang text */
        "delete from characteristics where id = :id";

    protected const UPDATE_BY_ID_SQL = /** @lang text */
        "update characteristics set title = :title, update_at = :update_at where id = :id;";

    public function findByCategoryId(int $id): array
    {
        $result = $this->db->getAll(self::SELECT_CHARACTERISTICS_BY_CATEGORY_ID, [
            'category_id' => $id
        ]);

        $res = [];
        foreach ($result as $r) {
            array_push($res, $this->mapFromArray($r));
        }

        return $res;
    }

    public function save(Characteristic $characteristic): Characteristic
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                'title' => $characteristic->getTitle(),
                'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $characteristic->setCreateAt($currentDateTime);
            $characteristic->setUpdateAt($currentDateTime);
            $characteristic->setId($this->db->insertId());

            return $characteristic;
        } catch (Exception $e) {
            $this->logException($e);
        }

        return new Characteristic();
    }

    public function update(Characteristic $char): Characteristic
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::UPDATE_BY_ID_SQL, [
                "title" => $char->getTitle(),
                "update_at" => $currentDateTime->format(Constants::DATETIME_FORMAT),
                "id" => $char->getId()
            ]);
            $char->setUpdateAt($currentDateTime);
            return $char;

        } catch (Exception $e) {
            $this->logException($e);
        }

        return new Characteristic();
    }

    public function delete(Characteristic $char)
    {
        $this->db->execute(self::DELETE_BY_ID_SQL, [
            "id" => $char->getId()
        ]);
    }
}

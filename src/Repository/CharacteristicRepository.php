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

    protected const INSERT_SQL = /** @lang MySQL */
            'INSERT INTO characteristics(title, create_at, update_at) VALUE (:title, :create_at, :update_at)';

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
}
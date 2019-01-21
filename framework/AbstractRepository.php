<?php

namespace Framework;

use DateTime;
use Exception;
use ReflectionClass;
use Zaine\Log;

abstract class AbstractRepository
{
    protected const MODEL_CLASS = "";

    protected const SELECT_ALL_SQL = "";
    protected const SELECT_COUNT_SQL = "";

    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findOne(string $where = '', array $param = []): AbstractModel
    {
        $query = static::SELECT_ALL_SQL;
        $query .= (!empty($where)) ? " WHERE $where" : '';

        $result = $this->db->getOne($query, $param);

        if (empty($result)) {
            try {
                $class = new ReflectionClass(static::MODEL_CLASS);
                return AbstractModel::fromObject($class->newInstance());
            } catch (\ReflectionException $e) {
                $this->logException($e);
                return null;
            }
        }

        return $this->mapFromArray($result);
    }

    public function findAll(string $where = '', array $param = []): array
    {
        $query = static::SELECT_ALL_SQL;
        $query .= (!empty($where)) ? " WHERE $where" : '';
        $query .= (array_key_exists('from', $param)) ? ' LIMIT :from' : '';
        $query .= (array_key_exists('count', $param)) ? ', :count' : '';

        $result = $this->db->getAll($query, $param);
        $res = [];

        foreach ($result as $r) {
            array_push($res, $this->mapFromArray($r));
        }

        return $res;
    }

    public function findById(int $id): AbstractModel
    {
        return $this->findOne('id = :id', [
                'id' => $id
        ]);
    }

    public function findCount(string $where = '', array $param = []): int
    {
        $query = static::SELECT_COUNT_SQL;
        $query .= (!empty($where)) ? " WHERE $where" : '';
        $result = $this->db->getOne($query, $param);

        return $result['count'];
    }

    protected function mapFromArray(array $data): AbstractModel
    {
        try {
            $data['create_at'] = $this->makeDateTime($data['create_at']);
            $data['update_at'] = $this->makeDateTime($data['update_at']);
            $class = new ReflectionClass(static::MODEL_CLASS);
            return AbstractModel::fromObject($class->newInstance())->fromArray($data);
        } catch (\ReflectionException $e) {
            $this->logException($e);
        }
        return null;
    }

    protected function makeDateTime(string $dateTime): DateTime
    {
        return DateTime::createFromFormat(
                Constants::DATETIME_FORMAT, $dateTime
        );
    }

    protected function logException(Exception $e): void
    {
        $logger = Dispatcher::get(Log::class);
        $logger->error($e->getMessage());
        $logger->error($e->getTraceAsString());
    }
}

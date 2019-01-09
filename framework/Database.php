<?php

namespace Framework;

use PDO;
use PDOException;
use Zaine\Log;

class Database
{
    private static $instance;
    private $pdo;
    private $logger;

    private const LAST_INSERT_ID_SQL = "SELECT LAST_INSERT_ID() as id;";

    public static function getInstance(): Database
    {
        if (null === static::$instance)
            static::$instance = new static();

        return static::$instance;
    }

    private function __construct()
    {
        $host = Config::get("db_host");
        $db = Config::get("db_name");
        $user = Config::get("db_user");
        $pass = Config::get("db_password");
        $charset = Config::get("db_charset");

        $this->logger = new Log("Database");

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $opt);
        } catch (PDOException  $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }
    }

    public function getAll(string $query, array $param = []): array
    {
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute($param);

            $res = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                array_push($res, ($row !== false) ? $row : []);

            return $res;
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }
        return [];
    }

    public function getOne(string $query, array $param = []): array
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($param);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($row !== false) ? $row : [];
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }
        return [];
    }

    public function execute(string $query, array $param = []): void
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($param);
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }
    }

    /**
     * @return int last inserted id or -1 if don`t find last inserted id
     */
    public function insertId(): int
    {
        try {
            $stmt = $this->pdo->prepare(self::LAST_INSERT_ID_SQL);
            $stmt->execute([]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }
        return -1;
    }

    /**
     * For singleton
     */
    private function __clone()
    {
    }

    /**
     * For singleton
     */
    private function __wakeup()
    {
    }
}
<?php

namespace Framework;

use PDO;
use PDOException;

class Database
{
    private static $instance;
    private $pdo;

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
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($param);

        $res = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            array_push($res, ($row !== false) ? $row : []);

        return $res;
    }

    public function getOne(string $query, array $param = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($param);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row !== false) ? $row : [];
    }

    public function execute(string $query, array $param = []): void
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($param);
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
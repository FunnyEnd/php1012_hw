<?php

namespace Framework\HTTP;

use InvalidArgumentException;

class Request
{
    private static $instance = null;
    protected $getData = [];
    protected $postData = [];
    protected $putData = [];

    private function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            parse_str(file_get_contents("php://input"), $post_vars);
            $this->putData = $post_vars;
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->postData = $_POST;
        }
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function exist(string $method, string $field)
    {
        $method = strtoupper($method);

        switch ($method) {
            case 'GET':
                return isset($this->getData[$field]);
            case 'POST':
                return isset($this->postData[$field]);
            case 'PUT':
                return isset($this->putData[$field]);
            default:
                return false;
        }
    }

    public function fetch(string $method, string $field){
        $method = strtoupper($method);

        if(!$this->exist($method, $field)){
            throw new InvalidArgumentException("Invalid {$method} argument `{$field}`.");
        }

        switch ($method) {
            case 'GET':
                return $this->getData[$field];
            case 'POST':
                return $this->postData[$field];
            case 'PUT':
                return $this->putData[$field];
        }

        return null;
    }

    public function setGetData(array $param): void
    {
        $this->getData = $param;
    }

    /**
     * @param string $param
     * @return string
     * @deprecated
     */
    public function get(string $param): string
    {
        if (isset($this->getData[$param])) {
            return $this->getData[$param];
        } else {
            throw new InvalidArgumentException("Invalid get argument `{$param}`.");
        }
    }

    /**
     * @param string $param
     * @return bool
     * @deprecated
     */
    public function issetGet(string $param): bool
    {
        return isset($this->getData[$param]);
    }

    public function setPostData(array $param): void
    {
        $this->postData = $param;
    }

    /**
     * @param string $param
     * @return mixed
     * @deprecated
     */
    public function post(string $param)
    {
        if (isset($this->postData[$param])) {
            return $this->postData[$param];
        } else {
            throw new InvalidArgumentException("Invalid post argument `{$param}`.");
        }
    }

    /**
     * @param string $param
     * @return mixed
     * @deprecated
     */
    public function put(string $param)
    {
        if (isset($this->putData[$param])) {
            return $this->putData[$param];
        } else {
            throw new InvalidArgumentException("Invalid update argument.");
        }
    }

    private function __clone()
    {
    }

    // 0 => method, 1 => field, 2 => regexp, 3 => message
    public function check(array $data): string
    {
        foreach ($data as $dataRow) {
            if (!$this->exist($dataRow[0], $dataRow[1])) {
                return $dataRow[3];
            }

            if ($dataRow[2] == ':email') {
                $dataRow[2] = '/^[a-z0-9._-]{3,}\@[a-z0-9._-]{3,}\.[a-z0-9]{2,}$/iD';
            }

            if ($dataRow[2] == ':password') {
                $dataRow[2] = '/^\w{4,32}$/';
            }


            if (!preg_match($dataRow[2], $this->fetch($dataRow[0], $dataRow[1]))) {
                return $dataRow[3];
            };
        }

        return '';
    }
}

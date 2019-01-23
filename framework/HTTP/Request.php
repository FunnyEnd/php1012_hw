<?php

namespace Framework\HTTP;

use InvalidArgumentException;

class Request
{
    private static $instance = null;
    protected $getData = array();
    protected $postData = array();
    protected $putData = array();

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

    public function setGetData(array $param): void
    {
        $this->getData = $param;
    }

    /**
     * Read data.
     * @param string $param
     * @return string
     */
    public function get(string $param): string
    {
        if (isset($this->getData[$param])) {
            return $this->getData[$param];
        } else {
            throw new InvalidArgumentException("Invalid get argument.");
        }
    }

    public function issetGet(string $param): bool
    {
        return isset($this->getData[$param]);
    }

    /**
     * Insert data.
     * @param string $param
     * @return mixed
     */
    public function post(string $param)
    {
        if (isset($this->postData[$param])) {
            return $this->postData[$param];
        } else {
            throw new InvalidArgumentException("Invalid post argument.");
        }
    }

    /**
     * Update data.
     * @param string $param
     * @return mixed
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
}

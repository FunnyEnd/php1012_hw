<?php

namespace Framework\HTTP;

use InvalidArgumentException;

class Request
{
    protected $getData = array();
    protected $postData = array();
    protected $putData = array();
//    protected $deleteData = array();

    private static $instance = null;

    private function __construct()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'PUT':
                {
                    parse_str(file_get_contents("php://input"), $post_vars);
                    $this->putData = $post_vars;
                    break;
                }
            case 'POST':
                {
                    $this->postData = $_POST;
                    break;
                }
        }
    }

    private function __clone()
    {
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
        if (isset($this->getData[$param]))
            return $this->getData[$param];
        else
            throw new InvalidArgumentException("Invalid get argument.");
    }

    /**
     * Insert data.
     * @param string $param
     * @return mixed
     */
    public function post(string $param)
    {
        if (isset($this->postData[$param]))
            return $this->postData[$param];
        else
            throw new InvalidArgumentException("Invalid post argument.");
    }

    /**
     * Delete data.
     * @param string $param
     * @return mixed
     */
//    public function delete(string $param)
//    {
//        if (isset($this->deleteData[$param]))
//            return $this->deleteData[$param];
//        else
//            throw new InvalidArgumentException("Invalid delete argument.");
//    }

    /**
     * Update data.
     * @param string $param
     * @return mixed
     */
    public function put(string $param)
    {
        if (isset($this->putData[$param]))
            return $this->putData[$param];
        else
            throw new InvalidArgumentException("Invalid update argument.");
    }
}

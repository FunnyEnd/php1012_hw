<?php

namespace Framework\HTTP;

use InvalidArgumentException;

class Request
{
    protected $getData = array();
    protected $postData = array();
    protected $updateData = array();
    protected $deleteData = array();

    private static $instance = null;

    private function __construct()
    {
        if (isset($_POST['__method'])) {
            switch ($_POST['__method']) {
                case 'update':
                    {
                        $this->updateData = $_POST;
                        break;
                    }
                case 'delete':
                    {
                        $this->deleteData = $_POST;
                        break;
                    }
            }
        } else {
            $this->postData = $_POST;
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

    /**
     * @todo rewrite this
     * @param array $param
     */
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
            throw new InvalidArgumentException("Invalid get argument.");
    }

    /**
     * Delete data.
     * @param string $param
     * @return mixed
     */
    public function delete(string $param)
    {
        if (isset($this->deleteData[$param]))
            return $this->deleteData[$param];
        else
            throw new InvalidArgumentException("Invalid get argument.");
    }

    /**
     * Update data.
     * @param string $param
     * @return mixed
     */
    public function update(string $param)
    {
        if (isset($this->updateData[$param]))
            return $this->updateData[$param];
        else
            throw new InvalidArgumentException("Invalid get argument.");
    }
}

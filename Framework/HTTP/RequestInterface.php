<?php

namespace Framework\HTTP;

interface RequestInterface
{
    /**
     * Set get param from URI by Rout pattern
     * @param array $param
     */
    public function setGetData(array $param): void;

    /**
     * Read data.
     * @param string $param
     * @return string
     */
    public function get(string $param): string;

    /**
     * Insert data.
     * @param string $param
     * @return mixed
     */
    public function post(string $param);

    /**
     * Delete data.
     * @param string $param
     * @return mixed
     */
    public function delete(string $param);

    /**
     * Update data.
     * @param string $param
     * @return mixed
     */
    public function update(string $param);

    /**
     * Valid input data.
     * @return bool
     */
    public function valid(): bool;
}
<?php

namespace Framework;

abstract class BaseRepository
{
    protected $db;

    /**
     * @todo global DATETIME_FORMAT const
     */
    protected const DATETIME_FORMAT = "Y-m-d H:i:s";

    /**
     * @todo storageAdapter
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
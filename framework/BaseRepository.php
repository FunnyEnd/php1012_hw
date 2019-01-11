<?php

namespace Framework;

abstract class BaseRepository
{
    protected $db;

    /**
     * @todo storageAdapter
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }


//    abstract public function findById(int $id);
}
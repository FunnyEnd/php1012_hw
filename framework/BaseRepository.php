<?php

namespace Framework;

abstract class BaseRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
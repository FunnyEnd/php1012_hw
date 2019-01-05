<?php

namespace Framework;

use DateTime;

abstract class BaseModel
{
    protected $create_at;
    protected $update_at;

    public function getCreateAt(): DateTime
    {
        return $this->create_at;
    }

    public function setCreateAt(DateTime $create_at): void
    {
        $this->create_at = $create_at;
    }

    public function getUpdateAt(): DateTime
    {
        return $this->update_at;
    }

    public function setUpdateAt(DateTime $update_at): void
    {
        $this->update_at = $update_at;
    }
}
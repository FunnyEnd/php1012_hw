<?php

namespace App\Models;

use Framework\BaseModel;

class Image extends BaseModel
{
    private $path;
    private $alt;

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }

    public function formArray(array $data): void
    {
        $this->setPath($data['path']);
        $this->setAlt($data['alt']);
        $this->setCreateAt($data['create_at']);
        $this->setUpdateAt($data['update_at']);
    }

}
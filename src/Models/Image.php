<?php

namespace App\Models;

use Framework\AbstractModel;

class Image extends AbstractModel
{
    private $id;
    private $path;
    private $alt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Image
    {
        $this->id = $id;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): Image
    {
        $this->path = $path;
        return $this;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): Image
    {
        $this->alt = $alt;
        return $this;
    }

    public function fromArray(array $data): AbstractModel
    {
        $this->setId($data['id']);
        $this->setPath($data['path']);
        $this->setAlt($data['alt']);

        return parent::fromArray($data);
    }

}
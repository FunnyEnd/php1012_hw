<?php

namespace App\Models;

use Framework\AbstractModel;

class Characteristic extends AbstractModel
{
    private $id;
    private $title;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Characteristic
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Characteristic
    {
        $this->title = $title;
        return $this;
    }

    public function fromArray(array $data): AbstractModel
    {
        $this->setId($data['id']);
        $this->setTitle($data['title']);

        return parent::fromArray($data);
    }
}

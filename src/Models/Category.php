<?php

namespace App\Models;

use Framework\BaseModel;

class Category extends BaseModel
{
    private $id;
    private $title;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function formArray(array $data): void
    {
        $this->setId($data['id']);
        $this->setTitle($data['title']);
        $this->setCreateAt($data['create_at']);
        $this->setUpdateAt($data['update_at']);
    }

}
<?php

namespace Framework;

use DateTime;

abstract class AbstractModel
{
    protected $create_at;
    protected $update_at;

    public function __construct($abstractModel = null)
    {
        if ($abstractModel !== null && get_class($abstractModel) === get_class($this)) {
            $properties = $abstractModel->getProperties();
            foreach ($properties as $key => $property) {
                $this->$key = $property;
            }
        }
    }

    public function getCreateAt(): DateTime
    {
        return $this->create_at;
    }

    public function setCreateAt(DateTime $create_at): AbstractModel
    {
        $this->create_at = $create_at;
        return $this;
    }

    public function getUpdateAt(): DateTime
    {
        return $this->update_at;
    }

    public function setUpdateAt(DateTime $update_at): AbstractModel
    {
        $this->update_at = $update_at;
        return $this;
    }

    public function isEmpty(): bool
    {
        $class = get_class($this);
        $obj = new $class();

        return $obj == $this;
    }

    public function fromArray(array $data): AbstractModel
    {
        $this->setCreateAt($data['create_at']);
        $this->setUpdateAt($data['update_at']);

        return $this;
    }

    public static function fromObject($object): AbstractModel
    {
        return $object;
    }

    public function getProperties()
    {
        return get_object_vars($this);
    }
}

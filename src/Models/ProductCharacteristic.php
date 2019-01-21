<?php

namespace App\Models;

use Framework\AbstractModel;

class ProductCharacteristic extends AbstractModel
{
    protected $product;
    protected $characteristic;
    protected $value;

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): ProductCharacteristic
    {
        $this->product = $product;
        return $this;
    }

    public function getCharacteristic(): Characteristic
    {
        return $this->characteristic;
    }

    function setCharacteristic(Characteristic $characteristic): ProductCharacteristic
    {
        $this->characteristic = $characteristic;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): ProductCharacteristic
    {
        $this->value = $value;
        return $this;
    }

    public function fromArray(array $data): AbstractModel
    {
        $this->setProduct($data['product']);
        $this->setCharacteristic($data['characteristic']);
        $this->setValue($data['value']);

        return parent::fromArray($data);
    }
}
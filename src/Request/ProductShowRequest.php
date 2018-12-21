<?php
namespace App\Request;

use Framework\HTTP\Request;
use InvalidArgumentException;

class ProductShowRequest extends Request
{
    public function valid(): bool
    {
        try{
            $this->get('id');
        } catch (InvalidArgumentException $ie){
            return false;
        }

        return true;
    }
}
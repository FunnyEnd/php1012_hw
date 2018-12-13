<?php

namespace App\Collection;

use App\Models\Rout;

class RoutCollection
{
    private $routArray;

    public function __construct()
    {
        $this->routArray = array();
    }

    public function addRout(Rout $rout): void
    {
        array_push($this->routArray, $rout);
    }

    public function calCurrentRout(): bool
    {
        foreach ($this->routArray as $rout) {
            if ($rout->isEqCurRequest()) {
                $rout->executeController();
                return true;
            }
        }

        return false;
    }
}
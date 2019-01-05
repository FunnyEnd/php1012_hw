<?php
/**
 * Created by PhpStorm.
 * User: FoFF
 * Date: 05.01.2019
 * Time: 14:07
 */

namespace Framework;


use ReflectionClass;
use ReflectionException;

class Dispatcher
{
    private $services = [];

    private $instantiated = [];

    private $shared = [];

    public function addInstance(string $class, $service, bool $share = true)
    {
        $this->services[$class] = $service;
        $this->instantiated[$class] = $service;
        $this->shared[$class] = $share;
    }

    public function addClass(string $class, array $params, bool $share = true)
    {
        $this->services[$class] = $params;
        $this->shared[$class] = $share;
    }

    public function has(string $interface): bool
    {
        return isset($this->services[$interface]) || isset($this->instantiated[$interface]);
    }

    public function get(string $class)
    {
        if (isset($this->instantiated[$class]) && $this->shared[$class])
            return $this->instantiated[$class];

        $args = $this->services[$class];

        try {
            $object = (new ReflectionClass($class))->newInstanceArgs($args);

            if ($this->shared[$class])
                $this->instantiated[$class] = $object;

            return $object;
        } catch (ReflectionException $e) {
            die($e->getMessage());
        }
    }
}
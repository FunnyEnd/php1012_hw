<?php

namespace Framework;

use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;
use Zaine\Log;

abstract class BaseController
{
    protected $logger;

    public function __construct()
    {
        $this->logger = new Log(get_class($this));
    }

    public function hasMethod(string $method): bool
    {
        return method_exists($this, $method);
    }

    public function methodHasRequestParam(string $method): bool
    {
        try {
            if (!$this->hasMethod($method)) {
                $className = get_class($this);
                throw new UnexpectedValueException(
                        "Oh, method {$method} don`t exit at {$className}.");
            }
            $reflector = new ReflectionClass(get_class($this));
            if (count($reflector->getMethod($method)->getParameters()) != 0)
                return true;

        } catch (ReflectionException $e) {
            $this->logger->error($e->getMessage());
        }

        return false;
    }

    public function callMethod(string $method, array $param = array()): string
    {
        if (!$this->hasMethod($method)) {
            $className = get_class($this);
            throw new UnexpectedValueException(
                    "Oh, method {$method} don`t exit at {$className}.");
        }
        return call_user_func_array(array($this, $method), $param);
    }
}
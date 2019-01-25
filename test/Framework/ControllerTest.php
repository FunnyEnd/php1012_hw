<?php

namespace Test\Framework;

use Framework\Controller;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class ControllerTest extends TestCase
{
    protected const EXIST_METHOD_NAME = 'hasMethod';
    protected const DONT_EXIST_METHOD_NAME = 'someMethod';

    protected $controller;

    public function setUp()
    {
        try {
            $this->controller = $this->getMockForAbstractClass(Controller::class);
        } catch (\ReflectionException $e) {
            $this->fail($e->getMessage());
        }

        parent::setUp();
    }

    public function testHasMethod()
    {
        $this->assertTrue($this->controller->hasMethod(self::EXIST_METHOD_NAME));
    }

    public function testCallMethod()
    {
        $this->assertTrue($this->controller->callMethod(self::EXIST_METHOD_NAME, [self::EXIST_METHOD_NAME]));
    }

    public function testCallIncorrectMethod()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->assertTrue($this->controller->callMethod(self::DONT_EXIST_METHOD_NAME));
    }
}

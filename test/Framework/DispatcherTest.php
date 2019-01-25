<?php

namespace Test\Framework;

use Framework\Dispatcher;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{

    private const RANDOM_CLASS_NAME = 'RandomClassName';

    public function testAddAndGetClass()
    {
        Dispatcher::addClass(Foo::class, []);
        $foo = Dispatcher::get(Foo::class);
        $this->assertEquals(Foo::class, get_class($foo));
    }

    public function testGetIncorrectClass()
    {
        $bar = Dispatcher::get(Bar::class);
        $this->assertEquals(null, $bar);
    }

    public function testGetIncorrectClass2()
    {
        $exception = Dispatcher::get(self::RANDOM_CLASS_NAME);
        $this->assertEquals(null, $exception);
    }

    public function testAddAndGetInstance()
    {
        $bar = new Bar();
        Dispatcher::addInstance(Bar::class, $bar);
        $bar = Dispatcher::get(Bar::class);
        $this->assertEquals(Bar::class, get_class($bar));
    }

    public function testHasClass()
    {
        Dispatcher::addClass(Foo::class, []);
        $this->assertTrue(Dispatcher::has(Foo::class));
    }

    public function testHasInstance()
    {
        $bar = new Bar();
        Dispatcher::addInstance(Bar::class, $bar);
        $this->assertTrue(Dispatcher::has(Bar::class));
    }
}

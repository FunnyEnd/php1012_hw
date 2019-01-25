<?php

namespace Test\Framework;

use Framework\Config;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private const CONFIG_DATA = 'data';
    private const CONFIG_DATA_WITH_EXCEPTION = 'data_ex';
    private const CONFIG_VALUE = 'value';

    public function testSetAndGet()
    {
        Config::set(self::CONFIG_DATA, self::CONFIG_VALUE);
        $this->assertEquals(self::CONFIG_VALUE, Config::get(self::CONFIG_DATA));
    }

    public function testGetWithException()
    {
        $this->expectException(InvalidArgumentException::class);
        Config::get(self::CONFIG_DATA_WITH_EXCEPTION);
    }

    public function testExistConfig()
    {
        Config::set(self::CONFIG_DATA, self::CONFIG_VALUE);
        $this->assertTrue(Config::exist(self::CONFIG_DATA));
    }
}

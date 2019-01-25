<?php

namespace Test\Framework;

use DateTime;
use Exception;
use Framework\AbstractModel;
use PHPUnit\Framework\TestCase;

class AbstractModelTest extends TestCase
{
    protected $abstractModel;

    public function setUp()
    {
        try {
            $this->abstractModel = $this->getMockForAbstractClass(AbstractModel::class);
        } catch (\ReflectionException $e) {
            $this->fail($e->getMessage());
        }

        parent::setUp();
    }

    public function testSetAndGetCreateAtDatetime()
    {
        try {
            $dateTime = new DateTime();
            $this->abstractModel->setCreateAt($dateTime);
            $this->assertEquals($dateTime, $this->abstractModel->getCreateAt());
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testSetAndGetUpdateAtDatetime()
    {
        try {
            $dateTime = new DateTime();
            $this->abstractModel->setUpdateAt($dateTime);
            $this->assertEquals($dateTime, $this->abstractModel->getUpdateAt());
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testCheckEmptyObject()
    {
        $this->assertTrue($this->abstractModel->isEmpty());
    }

    public function testCreateObjectFromArray()
    {
        try {
            $dateTime = new DateTime();

            $data = [
                'create_at' => $dateTime,
                'update_at' => $dateTime
            ];

            $this->abstractModel->fromArray($data);

            $this->assertEquals($dateTime, $this->abstractModel->getCreateAt());
            $this->assertEquals($dateTime, $this->abstractModel->getUpdateAt());
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testGetProperties()
    {
        try {
            $dateTime = new DateTime();

            $data = [
                'create_at' => $dateTime,
                'update_at' => $dateTime
            ];

            $this->abstractModel->fromArray($data);

            $properties = $this->abstractModel->getProperties();
            $this->assertEquals($dateTime, $properties['create_at']);
            $this->assertEquals($dateTime, $properties['update_at']);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testConvertFromObjectToAbstractModel()
    {
        $object = (object)$this->abstractModel;
        $this->assertEquals(get_class($this->abstractModel), get_class(AbstractModel::fromObject($object)));
    }
}

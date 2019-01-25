<?php

namespace Test\Framework;

use Framework\View;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testRender()
    {
        $data = [
            'testData' => 'testData'
        ];

        $html = View::render('test_template', $data, 'test/Resources/');

        $this->assertEquals('testDataData', $html);
    }

    public function testIncorrectRender()
    {
        $html = View::render('test_template2', [], 'test/Resources/');
        $this->assertEquals('', $html);
    }
}

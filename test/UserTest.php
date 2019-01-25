<?php

class UserTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptyUser()
    {
        $user = new \App\Models\User();
        $this->assertTrue($user->isEmpty());
    }
}
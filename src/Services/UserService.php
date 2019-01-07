<?php

namespace App\Services;


class UserService
{
    public function hashPassword(string $password): string
    {
        return hash("sha512", $password);
    }
}
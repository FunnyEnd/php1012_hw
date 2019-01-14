<?php
return [
        [\App\Repository\ProductRepository::class,
                [new \App\Repository\CategoryRepository(), new \App\Repository\ImageRepository()]],
        [\App\Repository\CategoryRepository::class, []],
        [\App\Repository\ImageRepository::class, []],
        [\App\Repository\UsersRepository::class, []],
        [\App\Services\AuthService::class,
                [\Framework\Session::getInstance(), new \App\Repository\UsersRepository(), new \App\Services\UserService()]],
        [\App\Services\UserService::class, []],
        [\App\Repository\BasketProductRepository::class, []],
        [\App\Services\BasketService::class, [new \App\Repository\BasketProductRepository]]
];
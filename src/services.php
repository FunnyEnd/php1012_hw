<?php
return [
        [\App\Repository\ProductRepository::class,
                [new \App\Repository\CategoryRepository(), new \App\Repository\ImageRepository()]],
        [\App\Repository\CategoryRepository::class, []],
        [\App\Repository\ImageRepository::class, []],
        [\App\Repository\UsersRepository::class, []],
        [\App\Services\AuthService::class,
                [\Framework\Dispatcher::get(\Framework\Session::class), new \App\Repository\UsersRepository(), new \App\Services\UserService()]],
        [\App\Services\UserService::class, []],
        [\App\Repository\BasketProductRepository::class, []],
        [\App\Services\BasketService::class,
                [new \App\Repository\BasketProductRepository(), new \App\Repository\BasketRepository(),
                        \Framework\Dispatcher::get(\Framework\Session::class)]],
        [\App\Repository\BasketRepository::class, []],
        [\App\Repository\ContactPersonRepository::class, []],
        [\App\Repository\OrderRepository::class, []],
    [\App\Repository\OrderProductRepository::class, []]
];
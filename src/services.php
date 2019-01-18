<?php

\Framework\Dispatcher::addClass(\App\Repository\ProductRepository::class, []);
\Framework\Dispatcher::addClass(\App\Repository\CategoryRepository::class, []);
\Framework\Dispatcher::addClass(\App\Repository\ImageRepository::class, []);
\Framework\Dispatcher::addClass(\App\Repository\UsersRepository::class, []);
\Framework\Dispatcher::addClass(\App\Repository\BasketRepository::class, []);
\Framework\Dispatcher::addClass(\App\Repository\BasketProductRepository::class, []);
\Framework\Dispatcher::addClass(\App\Repository\ContactPersonRepository::class, []);
\Framework\Dispatcher::addClass(\App\Services\UserService::class, [
        \Framework\Dispatcher::get(\App\Repository\ContactPersonRepository::class),
        \Framework\Dispatcher::get(\App\Repository\UsersRepository::class)
]);
\Framework\Dispatcher::addClass(\App\Services\AuthService::class, [
        \Framework\Dispatcher::get(\Framework\Session::class),
        \Framework\Dispatcher::get(\App\Repository\UsersRepository::class),
        \Framework\Dispatcher::get(\App\Services\UserService::class)
]);
\Framework\Dispatcher::addClass(\App\Services\BasketService::class, [
        \Framework\Dispatcher::get(\App\Repository\BasketProductRepository::class),
        \Framework\Dispatcher::get(\App\Repository\BasketRepository::class),
        \Framework\Dispatcher::get(\Framework\Session::class),
        \Framework\Dispatcher::get(\App\Repository\ProductRepository::class),
        \Framework\Dispatcher::get(\App\Services\AuthService::class)
]);
\Framework\Dispatcher::addClass(\App\Repository\OrderRepository::class, []);
\Framework\Dispatcher::addClass(\App\Repository\OrderProductRepository::class, []);
\Framework\Dispatcher::addClass(\App\Services\ProductService::class, [
        \Framework\Dispatcher::get(\App\Repository\ProductRepository::class)
]);
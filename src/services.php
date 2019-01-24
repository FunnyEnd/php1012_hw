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
\Framework\Dispatcher::addClass(\App\Repository\OrderRepository::class, []);
\Framework\Dispatcher::addClass(\App\Repository\OrderProductRepository::class, []);

\Framework\Dispatcher::addClass(\App\Services\Basket\BasketDataBaseService::class, [
    \Framework\Dispatcher::get(\App\Repository\BasketProductRepository::class),
    \Framework\Dispatcher::get(\App\Services\AuthService::class),
    \Framework\Dispatcher::get(\App\Repository\BasketRepository::class)
]);
\Framework\Dispatcher::addClass(\App\Services\Basket\BasketSessionService::class, [
    \Framework\Dispatcher::get(\Framework\Session::class),
    \Framework\Dispatcher::get(\App\Repository\ProductRepository::class)
]);
\Framework\Dispatcher::addClass(\App\Services\Basket\BasketServiceFactory::class, []);
\Framework\Dispatcher::addClass(\App\Services\OrderService::class, [
    \Framework\Dispatcher::get(\App\Repository\ContactPersonRepository::class),
    \Framework\Dispatcher::get(\App\Services\AuthService::class),
    \Framework\Dispatcher::get(\App\Repository\OrderRepository::class),
    \Framework\Dispatcher::get(\App\Services\Basket\BasketServiceFactory::class),
    \Framework\Dispatcher::get(\App\Repository\OrderProductRepository::class),
]);
\Framework\Dispatcher::addClass(\App\Services\ProductService::class, [
    \Framework\Dispatcher::get(\App\Repository\ProductRepository::class)
]);
\Framework\Dispatcher::addClass(\App\Services\SearchService::class, [
    \Framework\Dispatcher::get(\App\Repository\ProductRepository::class)
]);
\Framework\Dispatcher::addClass(\App\Repository\CharacteristicRepository::class, []);
\Framework\Dispatcher::addClass(\App\Repository\ProductCharacteristicsRepository::class, []);
\Framework\Dispatcher::addClass(\App\Services\FilterService::class, [
    \Framework\Dispatcher::get(\App\Repository\CharacteristicRepository::class),
    \Framework\Dispatcher::get(\App\Repository\ProductCharacteristicsRepository::class)
]);
\Framework\Dispatcher::addClass(\App\Services\Category\CategoryDefaultService::class, [
    \Framework\Dispatcher::get(\App\Repository\ProductRepository::class)
]);
\Framework\Dispatcher::addClass(\App\Services\Category\CategoryFilterService::class, [
    \Framework\Dispatcher::get(\App\Repository\ProductCharacteristicsRepository::class),
    \Framework\Dispatcher::get(\App\Repository\ProductRepository::class),
    \Framework\Dispatcher::get(\App\Services\FilterService::class)
]);

\Framework\Dispatcher::addClass(\App\Services\Admin\CategoryService::class, [
    \Framework\Dispatcher::get(\App\Repository\CategoryRepository::class)
]);
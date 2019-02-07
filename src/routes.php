<?php

namespace App;

use Framework\Routing\Route;
use Framework\Routing\Router;

// :d - digit
// :any - any chars

Router::addRout(new Route(
    Router::GET_METHOD,
    "/",
    'App\Controller\HomeController::index'
));
Router::addRout(new Route(
    Router::GET_METHOD,
    "/product/:d",
    "App\Controller\ProductController::index",
    ['id'],
    "App\Validators\ProductValidator::check"
));
Router::addRout(new Route(
    Router::GET_METHOD,
    "/category/:d",
    "App\Controller\CategoryController::index",
    ['id'],
    'App\Validators\CategoryValidator::check'
));

Router::addRout(new Route(
    Router::GET_METHOD,
    "/category/:d/page/:d",
    "App\Controller\CategoryController::index",
    ['id', 'page'],
    'App\Validators\CategoryValidator::check'
));

Router::addRout(new Route(
    Router::GET_METHOD,
    "/category/:d/page/:d/filter/:any",
    "App\Controller\CategoryController::index",
    ['id', 'page', 'filter'],
    'App\Validators\CategoryValidator::check'
));

Router::addRout(new Route(
    Router::GET_METHOD,
    "/auth",
    "App\Controller\AuthController::index"
));

Router::addRout(new Route(
    Router::POST_METHOD,
    "/auth",
    "App\Controller\AuthController::auth",
    [],
    "App\Validators\AuthValidator::check"
));

Router::addRout(new Route(
    Router::GET_METHOD,
    "/register",
    "App\Controller\RegisterController::index"
));

Router::addRout(new Route(
    Router::POST_METHOD,
    "/register",
    "App\Controller\RegisterController::register",
    [],
    "App\Validators\RegisterValidator::check"
));

Router::addRout(new Route(
    Router::GET_METHOD,
    "/logout",
    "App\Controller\AuthController::logout"
));

// basket routes
Router::addRout(new Route(
    Router::GET_METHOD,
    '/basket',
    'App\Controller\BasketController::index'
));

Router::addRout(new Route(
    Router::POST_METHOD,
    '/basket',
    'App\Controller\BasketController::store',
    [],
    'App\Validators\BasketValidator::checkStore'
));

Router::addRout(new Route(
    Router::PUT_METHOD,
    '/basket/:d',
    'App\Controller\BasketController::update',
    ['id'],
    'App\Validators\BasketValidator::checkUpdate'
));

Router::addRout(new Route(
    Router::DELETE_METHOD,
    '/basket/:d',
    'App\Controller\BasketController::delete',
    ['id'],
    'App\Validators\BasketValidator::checkDelete'
));

// order
Router::addRout(new Route(
    Router::GET_METHOD,
    '/order',
    'App\Controller\OrderController::index',
    [],
    'App\Validators\OrderValidator::check'
));
Router::addRout(new Route(
    Router::POST_METHOD,
    '/order',
    'App\Controller\OrderController::store',
    [],
    'App\Validators\OrderValidator::checkStore'
));

Router::addRout(new Route(
    Router::GET_METHOD,
    '/search/:any/page/:d',
    'App\Controller\SearchController::index',
    ['search-string', 'page']
));

Router::addRout(new Route(
    Router::GET_METHOD,
    '/search/:any',
    'App\Controller\SearchController::index',
    ['search-string'],
    'App\Validators\SearchValidator::check'
));

Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin',
    'App\Controller\Admin\HomeController::index'
));


Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin/order',
    'App\Controller\Admin\OrderController::list'
));


Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin/order/page/:d',
    'App\Controller\Admin\OrderController::list',
    ['page']
));

Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin/order/:d',
    'App\Controller\Admin\OrderController::index',
    ['id']
));

Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin/order/:d/confirm',
    'App\Controller\Admin\OrderController::confirm',
    ['id']
));

Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin/category',
    'App\Controller\Admin\CategoryController::index'
));
Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin/category/page/:d',
    'App\Controller\Admin\CategoryController::index',
    ['page']
));

Router::addRout(new Route(
    Router::POST_METHOD,
    '/admin/category',
    'App\Controller\Admin\CategoryController::store'
));

Router::addRout(new Route(
    Router::PUT_METHOD,
    '/admin/category/:d',
    'App\Controller\Admin\CategoryController::update',
    ['id']
));
Router::addRout(new Route(
    Router::DELETE_METHOD,
    '/admin/category/:d',
    'App\Controller\Admin\CategoryController::delete',
    ['id']
));

Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin/characteristic',
    'App\Controller\Admin\CharacteristicController::index'
));


Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin/characteristic/page/:d',
    'App\Controller\Admin\CharacteristicController::index',
    ['page']
));

Router::addRout(new Route(
    Router::POST_METHOD,
    '/admin/characteristic',
    'App\Controller\Admin\CharacteristicController::store'
));

Router::addRout(new Route(
    Router::PUT_METHOD,
    '/admin/characteristic/:d',
    'App\Controller\Admin\CharacteristicController::update',
    ['id']
));

Router::addRout(new Route(
    Router::DELETE_METHOD,
    '/admin/characteristic/:d',
    'App\Controller\Admin\CharacteristicController::delete',
    ['id'],
    "App\Validators\Admin\CharacteristicValidator::checkDelete"
));
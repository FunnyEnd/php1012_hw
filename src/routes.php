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
    ['id']
));
Router::addRout(new Route(
    Router::GET_METHOD,
    "/category/:d",
    "App\Controller\CategoryController::index",
    ['id']
));
Router::addRout(new Route(
    Router::GET_METHOD,
    "/category/:d/page/:d",
    "App\Controller\CategoryController::index",
    ['id', 'page']
));
Router::addRout(new Route(
    Router::GET_METHOD,
    "/category/:d/page/:d/filter/:any",
    "App\Controller\CategoryController::index",
    ['id', 'page', 'filter']
));
Router::addRout(new Route(
    Router::GET_METHOD,
    "/auth",
    "App\Controller\AuthController::index"
));
Router::addRout(new Route(
    Router::POST_METHOD,
    "/auth",
    "App\Controller\AuthController::auth"
));
Router::addRout(new Route(
    Router::GET_METHOD,
    "/register",
    "App\Controller\RegisterController::index"
));
Router::addRout(new Route(
    Router::POST_METHOD,
    "/register",
    "App\Controller\RegisterController::register"
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
    'App\Controller\BasketController::store'
));
Router::addRout(new Route(
    Router::PUT_METHOD,
    '/basket/:d',
    'App\Controller\BasketController::update',
    ['id']
));
Router::addRout(new Route(
    Router::DELETE_METHOD,
    '/basket/:d',
    'App\Controller\BasketController::delete',
    ['id']
));

// order
Router::addRout(new Route(
    Router::GET_METHOD,
    '/order',
    'App\Controller\OrderController::index'
));
Router::addRout(new Route(
    Router::POST_METHOD,
    '/order',
    'App\Controller\OrderController::store'
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
    ['search-string']
));

Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin',
    'App\Controller\Admin\HomeController::index'
));


Router::addRout(new Route(
    Router::GET_METHOD,
    '/admin/order',
    'App\Controller\Admin\OrderController::index'
));

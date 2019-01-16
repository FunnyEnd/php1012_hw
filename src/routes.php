<?php

namespace App;

use Framework\Routing\Router;
use Framework\Routing\Route;

// :d - digit
Router::addRout(new Route(
        Router::GET_METHOD,
        "/",
        'App\Controller\HomeController::showHome'
));
Router::addRout(new Route(
        Router::GET_METHOD,
        "/product/:d",
        "App\Controller\ProductController::showProduct",
        ['id']
));
Router::addRout(new Route(
        Router::GET_METHOD,
        "/category/:d",
        "App\Controller\CategoryController::showCategory",
        ['id']
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

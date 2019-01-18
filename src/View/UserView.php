<?php

namespace App\View;


use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\AuthService;
use App\Services\Basket\BasketDataBaseServiceFactory;
use App\Services\Basket\BasketSessionServiceFactory;
use Framework\Dispatcher;
use Exception;
use Framework\Session;

class UserView extends \Framework\View
{
    protected const PATH = "src/View/templates/";

    public static function render(string $template, array $data = array(), $templatePath = self::PATH): string
    {

        $authService = (Dispatcher::get(AuthService::class));
        $auth = $authService->isAuth();

        $categoryRepository = Dispatcher::get(CategoryRepository::class);
        $category = $category = $categoryRepository->findAll();

        if ($authService->isAuth()) {
            $basketServiceFactory = new BasketDataBaseServiceFactory(
                    Dispatcher::get(BasketProductRepository::class),
                    $authService,
                    Dispatcher::get(BasketRepository::class)
            );
            $basketService = $basketServiceFactory->getBasketService();
        } else {
            $basketServiceFactory = new BasketSessionServiceFactory(
                    Dispatcher::get(Session::class),
                    Dispatcher::get(ProductRepository::class)
            );
            $basketService = $basketServiceFactory->getBasketService();
        }

        $countProductsAtUserBasket = $basketService->getCountProducts();

        $additionData = [
                'auth' => $auth,
                'category' => $category,
                'countProductsAtUserBasket' => $countProductsAtUserBasket
        ];

        try {
            foreach ($additionData as $key => $value) {
                if (array_key_exists($key, $data) == true) {
                    throw new Exception("Field '{$key}' already exist.");
                }
            }
        } catch (Exception $e) {
            // todo log
            die($e->getMessage());
        }

        $data = array_merge($data, $additionData);
        return parent::render($template, $data, $templatePath);
    }

}
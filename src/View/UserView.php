<?php

namespace App\View;

use App\Repository\CategoryRepository;
use App\Services\AuthService;
use App\Services\Basket\BasketServiceFactory;
use Exception;
use Framework\Dispatcher;
use Framework\View;

class UserView extends View
{
    protected const PATH = "src/View/templates/";

    public static function render(string $template, array $data = array(), $templatePath = self::PATH): string
    {
        $authService = (Dispatcher::get(AuthService::class));
        $auth = $authService->isAuth();

        $categoryRepository = Dispatcher::get(CategoryRepository::class);
        $category = $category = $categoryRepository->findAll();

        $basketServiceFactory = Dispatcher::get(BasketServiceFactory::class);
        $basketService = $basketServiceFactory->getBasketService($authService->isAuth());
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
            die($e->getMessage());
        }

        $data = array_merge($data, $additionData);

        return parent::render($template, $data, $templatePath);
    }
}

<?php

namespace App\View;


use App\Repository\CategoryRepository;
use App\Services\AuthService;
use App\Services\BasketService;
use Framework\Dispatcher;
use Exception;

class UserView extends \Framework\View
{
    protected const PATH = "src/View/templates/";

    public static function render(string $template, array $data = array(), $templatePath = self::PATH): string
    {

        $authService = (Dispatcher::get(AuthService::class));
        $auth = $authService->isAuth();

        $categoryRepository = Dispatcher::get(CategoryRepository::class);
        $category = $category = $categoryRepository->findAll();

        if($auth) {
            $basketService = Dispatcher::get(BasketService::class);
            $countProductsAtUserBasket = $basketService->getCountProductsAtUserBasket($authService->getUserId());
        } else {
            $countProductsAtUserBasket = 0;
        }

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
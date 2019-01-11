<?php

namespace App\View;


use App\Repository\CategoryRepository;
use App\Services\AuthService;
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

        $additionData = [
                'auth' => $auth,
                'category' => $category
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
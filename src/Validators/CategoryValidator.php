<?php

namespace App\Validators;

use App\Repository\CategoryRepository;
use App\View\UserView;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Validator;

class CategoryValidator extends Validator
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function check(Request $request)
    {
        $error = $request->check([
            ['get', 'id', '/^[0-9]+$/', 'Id entered incorrectly.'],
        ]);

        if ($error == '') {
            $count = $this->categoryRepository->findCount('category.id = :id', [
                'id' => $request->fetch('get', 'id')
            ]);

            if ($count != 1) {
                Response::setResponseCode(404);
                $error = 'Category don`t exist.';
            }
        }

        if ($error == '' && $request->exist('get', 'page')) {
            $error = $request->check([
                ['get', 'page', '/^[1-9][0-9]*$/', 'Page entered incorrectly.']
            ]);
        }

        if ($error == '' && $request->exist('get', 'filter')) {
            $error = $request->check([
                ['get', 'filter', '/^[a-zA-Z0-9,;=]*$/', 'Filter entered incorrectly.']
            ]);
        }

        if ($error != '') {
            return UserView::render('category_dont_exist', [
                'error' => $error
            ]);
        }

        return '';
    }
}

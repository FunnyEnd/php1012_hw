<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Repository\CharacteristicRepository;
use App\View\AdminView;
use Framework\Config;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class CharacteristicController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * @todo move to service
     * @param Request $request
     * @param CharacteristicRepository $characteristicRepository
     * @return string
     */
    public function index(Request $request, CharacteristicRepository $characteristicRepository)
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $currentPage = 1;
        if ($request->exist('get', 'page')) {
            $currentPage = $request->fetch('get', 'page');
        }

        $countAtPage = Config::get('count_characteristic_at_page');
        $count = $characteristicRepository->findCount();

        if ($countAtPage > $count) {
            $countPages = 1;
        } else {
            $countPages = ceil($count / $countAtPage);
        }

        $from = ($currentPage - 1) * $countAtPage;

        $char = $characteristicRepository->findAll('', [
            'from' => $from,
            'count' => $countAtPage
        ]);

        return AdminView::render('characteristic', [
            'characteristic' => $char,
            'currentPage' => $currentPage,
            'countPages' => $countPages
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Services\Admin\CharacteristicService;
use App\View\AdminView;
use Framework\Dispatcher;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Session;

class CharacteristicController extends BaseController
{
    private $characteristicService;

    public function __construct(CharacteristicService $characteristicService)
    {
        $this->characteristicService = $characteristicService;
    }

    public function index(Request $request)
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $currentPage = $this->characteristicService->getCurrentPage($request);
        $countPages = $this->characteristicService->getCountPages();
        $char = $this->characteristicService->getCharacteristic($currentPage);
        $session = Dispatcher::get(Session::class);

        $error = '';
        if($session->keyExist('error')){
            $error = $session->get('error');
            $session->unset('error');
        }

        return AdminView::render('characteristic', [
            'characteristic' => $char,
            'currentPage' => $currentPage,
            'countPages' => $countPages,
            'error' => $error
        ]);
    }

    public function store(Request $request)
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $this->characteristicService->store($request);

        return Response::redirect('/admin/characteristic');
    }

    public function update(Request $request)
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $this->characteristicService->update($request);

        return Response::redirect('/admin/characteristic');
    }

    public function delete(Request $request)
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $this->characteristicService->delete($request);

        return Response::redirect('/admin/characteristic');
    }
}

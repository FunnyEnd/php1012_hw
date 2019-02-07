<?php

namespace App\Services\Admin;

use App\Models\Characteristic;
use App\Repository\CharacteristicRepository;
use Framework\Application;
use Framework\Config;
use Framework\HTTP\Request;

class CharacteristicService
{
    private $characteristicRepository;

    public function __construct(CharacteristicRepository $characteristicRepository)
    {
        $this->characteristicRepository = $characteristicRepository;
    }

    public function getCurrentPage(Request $request)
    {
        $currentPage = 1;

        if ($request->exist('get', 'page')) {
            $currentPage = $request->fetch('get', 'page');
        }

        return $currentPage;
    }

    public function getCountPages()
    {
        $countAtPage = Config::get('count_characteristic_at_page');
        $count = $this->characteristicRepository->findCount();
        $countPages = 1;

        if ($countAtPage < $count) {
            $countPages = ceil($count / $countAtPage);
        }

        return $countPages;
    }

    public function getCharacteristic(int $currentPage)
    {
        $countAtPage = Config::get('count_characteristic_at_page');
        $from = ($currentPage - 1) * $countAtPage;

        return $this->characteristicRepository->findAll('', [
            'from' => $from,
            'count' => $countAtPage
        ]);
    }

    public function store(Request $request)
    {
        return $this->characteristicRepository->save((new Characteristic())
            ->setTitle($request->fetch('post', 'title'))
        );
    }

    public function update(Request $request)
    {
        $char = $this->characteristicRepository->findById($request->fetch('get', 'id'));

        if($char->isEmpty()){
            return new Characteristic();
        }

        $char->setTitle($request->fetch('put', 'title'));

        return $this->characteristicRepository->update($char);
    }

    public function delete(Request $request): void
    {
        $id = $request->fetch('get', 'id');
        $char = $this->characteristicRepository->findById($id);

        if($char->isEmpty()){
            return ;
        }

        $char = (new Characteristic())
            ->setId($id);

        $this->characteristicRepository->delete($char);
    }

}
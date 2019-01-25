<?php

namespace App\Services;

use App\Repository\CharacteristicRepository;
use App\Repository\ProductCharacteristicsRepository;
use Framework\HTTP\Request;

class FilterService
{
    private $characteristicRepository;
    private $prodCharRep;

    public function __construct(
        CharacteristicRepository $characteristicRepository,
        ProductCharacteristicsRepository $prodCharRep
    ) {
        $this->characteristicRepository = $characteristicRepository;
        $this->prodCharRep = $prodCharRep;
    }

    // return [ char_id => [selected, values], ... ]
    public function getSelectedValues(Request $request): array
    {
        if ($request->issetGet('filter')) {
            $filter = $request->get('filter');
        } else {
            return [];
        }

        $char = explode(';', $filter);
        $filterParams = [];

        foreach ($char as $c) {
            $data = explode('=', $c);
            $params = explode(',', $data[1]);
            $filterParams[$data[0]] = $params;
        }

        return $filterParams;
    }

    public function getCharacteristics(Request $request)
    {
        $categoryId = $request->get('id');
        $chars = $this->characteristicRepository->findByCategoryId($categoryId);
        $filterSelectedValues = $this->getSelectedValues($request);
        $characteristics = [];

        foreach ($chars as $key => $cha) {
            $characteristics[$key]['info'] = $cha;
            $arr = $this->prodCharRep->findValuesByCategoryIdAndCharId($categoryId, $cha->getId());
            $charId = $cha->getId();
            $newArr = [];
            foreach ($arr as $a) {
                if (array_key_exists($charId, $filterSelectedValues) && in_array($a, $filterSelectedValues[$charId])) {
                    $newArr[] = [
                        'value' => $a,
                        'selected' => 1
                    ];
                } else {
                    $newArr[] = [
                        'value' => $a,
                        'selected' => 0
                    ];
                }
            }
            $characteristics[$key]['values'] = $newArr;
        }

        return $characteristics;
    }
}
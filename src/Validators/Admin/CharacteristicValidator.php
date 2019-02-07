<?php

namespace App\Validators\Admin;

use App\Repository\ProductCharacteristicsRepository;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Session;
use Framework\Validator;

class CharacteristicValidator extends Validator
{
    public function __construct()
    {
    }

    public function checkDelete(Request $request, ProductCharacteristicsRepository $repo, Session $session)
    {
        $error = $request->check([
            ['get', 'id', ':id', 'Id entered incorrectly.'],
        ]);

        if ($error == '') {
            $count = $repo->findCount('characteristic_id = :id', [
                'id' => $request->fetch('get', 'id')
            ]);

            if ($count != 0) {
                $error = 'Ohhh, cannot delete. Some products have this characteristic';
            }
        }

        if ($error != '') {
            $session->set('error', $error);
            return Response::redirect('/admin/characteristic/');
        }

        return '';
    }
}

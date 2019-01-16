<?php

namespace App\Controller;

use App\Models\ContactPerson;
use App\Repository\ContactPersonRepository;
use App\View\UserView;
use Framework\BaseController;

class OrderController extends BaseController
{
    public function __construct()
    {
    }

    public function index(ContactPersonRepository $contactPersonRepository)
    {
        $contactPerson = (new ContactPerson())
                ->setFirstName('Alex')
                ->setLastName('Kornienko')
                ->setEmail('email')
                ->setCity('city')
                ->setStock('stock')
                ->setPhone('0995402340');

        $newContactPerson = $contactPersonRepository->save($contactPerson);
        var_dump($newContactPerson);

        return "";
//        return UserView::render('order');
    }
}
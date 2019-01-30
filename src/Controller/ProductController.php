<?php

namespace App\Controller;

use App\Repository\ProductCharacteristicsRepository;
use App\Repository\ProductRepository;
use App\View\UserView;
use Framework\Controller;
use Framework\HTTP\Request;

class ProductController extends Controller
{
    private $productRepository;
    protected $prodCharRep;

    public function __construct(
        ProductRepository $productRepository,
        ProductCharacteristicsRepository $prodCharRep
    ) {
        $this->productRepository = $productRepository;
        $this->prodCharRep = $prodCharRep;
    }

    public function index(Request $request): string
    {
        $product = $this->productRepository->findById($request->fetch('get', 'id'));
        $characteristics = $this->prodCharRep->findByProductId($product->getId());

        return UserView::render('product_detailed', array(
            'product' => $product,
            'characteristics' => $characteristics
        ));
    }
}

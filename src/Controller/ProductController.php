<?php

namespace App\Controller;

use App\Repository\ProductCharacteristicsRepository;
use App\Repository\ProductRepository;
use App\View\UserView;
use Framework\Controller;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class ProductController extends Controller
{
    private $productRepository;
    protected $prodCharRep;

    function __construct(ProductRepository $productRepository, ProductCharacteristicsRepository $prodCharRep)
    {
        $this->productRepository = $productRepository;
        $this->prodCharRep = $prodCharRep;
    }

    public function index(Request $request): string
    {
        $product = $this->productRepository->findById($request->get('id'));

        if ($product->isEmpty()) {
            Response::setResponseCode(404);
            return UserView::render('404');
        }

        $characteristics = $this->prodCharRep->findByProductId($product->getId());

        return UserView::render('product_detailed', array(
                'product' => $product,
                'characteristics' => $characteristics
        ));
    }
}

<?php

namespace App\Controller;

use App\Extensions\BasketNotExistExtension;
use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Product;
use App\Models\User;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Services\AuthService;
use App\Services\BasketService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;

class BasketController extends BaseController
{
    private $authService;
    private $basketService;

    public function __construct(AuthService $authService, BasketService $basketService)
    {
        $this->authService = $authService;
        $this->basketService = $basketService;
    }

    public function index(BasketProductRepository $basketProductRepository)
    {
        if (!$this->authService->isAuth())
            return 'no auth';

        $products = $basketProductRepository->findByUserId($this->authService->getUserId());
        $totalPrice = $this->basketService->calculateTotalPrice($products);

        return UserView::render('basket', ['basketProducts' => $products, 'totalPrice' => $totalPrice]);
    }

    public function store(Request $request)
    {
        if (!$this->authService->isAuth())
            json_encode(['success' => false, 'error' => 'no auth']);

        $basketProduct = new BasketProduct();
        $basketProduct->setProduct((new Product())->setId($request->post('id')));
        $basketProduct->setBasket($this->basketService->getBasketByUserId($this->authService->getUserId()));
        $basketProduct->setCount($request->post('count'));
        $this->basketService->addProductToBasket($basketProduct);

        return json_encode(['success' => true]);
    }

}
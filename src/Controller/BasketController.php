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

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(BasketProductRepository $basketProductRepository)
    {
        if (!$this->authService->isAuth())
            return 'no auth';

        $products = $basketProductRepository->findByUserId($this->authService->getUserId());
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->getPriceAtCoins();
        }
        $totalPrice /= 100;

        return UserView::render('basket', ['basketProducts' => $products, 'totalPrice' => $totalPrice]);
    }

    public function store(Request $request, BasketService $basketService, BasketRepository $basketRepository)
    {
        if (!$this->authService->isAuth())
            json_encode(['success' => false, 'error' => 'no auth']);

        $userId = $this->authService->getUserId();
        $basketProduct = new BasketProduct();
        $product = new Product();
        $product->setId(intval($request->post('id')));
        $basketProduct->setProduct($product);

        $basket = null;

        try {
            $basket = $basketRepository->findByUserId($userId);
        } catch (BasketNotExistExtension $e) {
            $basket = new Basket();
            $user = new User();
            $user->setId($userId);
            $basket->setUser($user);
            $basket = $basketRepository->save($basket);
        }

        $basketProduct->setBasket($basket);
        $basketProduct->setCount(intval($request->post('count')));
        $basketService->addProductToBasket($basketProduct);

        return json_encode(['success' => true]);
    }

}
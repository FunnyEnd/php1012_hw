<?php

namespace App\Controller;

use App\Services\AuthService;
use App\Services\Basket\BasketServiceFactory;
use App\View\UserView;
use Framework\Controller;
use Framework\HTTP\Request;

class BasketController extends Controller
{
    private $authService;
    private $basketService;

    public function __construct(AuthService $authService, BasketServiceFactory $basketServiceFactory)
    {
        $this->authService = $authService;
        $this->basketService = $basketServiceFactory->getBasketService($this->authService->isAuth());
    }

    public function index()
    {
        $products = $this->basketService->getProducts();
        $totalPrice = $this->basketService->getTotalPrice();

        $error = null;
        if (count($products) == 0) {
            $error = 'Basket is empty';
        }

        return UserView::render('basket', [
            'error' => $error,
            'basketProducts' => $products,
            'totalPrice' => $totalPrice
        ]);
    }

    public function store(Request $request)
    {
        $this->basketService->addProduct($request);
        $countProductsAtUserBasket = $this->basketService->getCountProducts();

        return json_encode([
            'success' => true,
            'countProductsAtUserBasket' => $countProductsAtUserBasket
        ]);
    }

    public function update(Request $request)
    {
        $basketProduct = $this->basketService->updateProduct($request);
        if ($basketProduct->isEmpty()) {
            return json_encode([
                'success' => false
            ]);
        }

        return json_encode([
            'success' => true,
            'productTotalPrice' => $basketProduct->getPriceAtBills(),
            'totalPrice' => $this->basketService->getTotalPrice()
        ]);
    }

    public function delete(Request $request)
    {
        $this->basketService->deleteProduct($request);
        $totalPrice = $this->basketService->getTotalPrice();
        $countProductsAtUserBasket = $this->basketService->getCountProducts();

        return json_encode([
            'success' => true,
            'totalPrice' => $totalPrice,
            'countProductsAtUserBasket' => $countProductsAtUserBasket
        ]);
    }
}

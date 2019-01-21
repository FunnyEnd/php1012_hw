<?php

namespace App\Controller;

use App\Services\AuthService;
use App\Services\Basket\BasketServiceFactory;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;

class BasketController extends BaseController
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

        return UserView::render('basket', [
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

    // todo check id request param
    public function update(Request $request)
    {
        $success = true;
        if (intval($request->put('count')) <= 0) {
            $success = false;
        }

        $basketProduct = $this->basketService->updateProduct($request);
        if ($basketProduct->isEmpty()) {
            $success = false;
        }

        if ($success) {
            return json_encode([
                    'success' => $success,
                    'productTotalPrice' => $basketProduct->getPriceAtBills(),
                    'totalPrice' => $this->basketService->getTotalPrice()
            ]);
        } else {
            return json_encode([
                    'success' => $success
            ]);
        }
    }

    // todo check id request param
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
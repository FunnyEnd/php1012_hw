<?php

namespace App\Controller;

use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use App\Services\AuthService;
use App\Services\Basket\BasketDataBaseServiceFactory;
use App\Services\Basket\BasketSessionServiceFactory;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\Session;

class BasketController extends BaseController
{
    private $authService;
    private $basketService;

    public function __construct(AuthService $authService, BasketProductRepository $basketProductRepository,
                                Session $session, ProductRepository $productRepository,
                                BasketRepository $basketRepository)
    {
        $this->authService = $authService;

        if ($this->authService->isAuth()) {
            $basketServiceFactory = new BasketDataBaseServiceFactory(
                    $basketProductRepository,
                    $authService,
                    $basketRepository
            );
            $this->basketService = $basketServiceFactory->getBasketService();
        } else {
            $basketServiceFactory = new BasketSessionServiceFactory(
                    $session,
                    $productRepository
            );
            $this->basketService = $basketServiceFactory->getBasketService();
        }
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

        if ($basketProduct === null) {
            $success = false;
        }

        return json_encode([
                'success' => $success,
                'productTotalPrice' => $basketProduct->getPriceAtBills(),
                'totalPrice' => $this->basketService->getTotalPrice()
        ]);
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
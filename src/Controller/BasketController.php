<?php

namespace App\Controller;

use App\Models\BasketProduct;
use App\Models\Product;
use App\Repository\BasketProductRepository;
use App\Services\AuthService;
use App\Services\BasketService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Session;

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
        // todo add to BasketService getBasketProducts
        $products = $basketProductRepository->findByUserId($this->authService->getUserId());
        $totalPrice = $this->basketService->calculateTotalPrice($products);

        return UserView::render('basket', ['basketProducts' => $products, 'totalPrice' => $totalPrice]);
    }

    public function store(Request $request, Session $session)
    {
        if ($this->authService->isAuth()) {
            $userId = $this->authService->getUserId();
            $basketProduct = new BasketProduct();
            $basketProduct->setProduct((new Product())->setId($request->post('id')));
            $basketProduct->setBasket($this->basketService->getBasketByUserId($userId));
            $basketProduct->setCount($request->post('count'));
            $this->basketService->addProductToBasket($basketProduct);

            $countProductsAtUserBasket = $this->basketService->getCountProductsAtUserBasket();
        } else {
            $session->start();
            $basketProducts = $session->get('basketProducts');

            if (!is_array($basketProducts))
                $basketProducts = [];

            if (array_key_exists($request->post('id'), $basketProducts))
                $basketProducts[$request->post('id')] += $request->post('count');
            else
                $basketProducts[$request->post('id')] = $request->post('count');

            $countProductsAtUserBasket = count($basketProducts);

            $session->set('basketProducts', $basketProducts);
        }

        return json_encode(['success' => true, 'countProductsAtUserBasket' => $countProductsAtUserBasket]);
    }

    public function update(Request $request, BasketProductRepository $basketProductRepository)
    {
        if (!$this->authService->isAuth())
            return json_encode(['success' => false, 'auth' => false]);

        $count = $request->put('count');

        if (intval($count) <= 0)
            return json_encode(['success' => false, 'error' => 'Not available value.']);

        $basketProduct = $this->basketService->updateProductCountAtBasket(
                $request->get('id'),
                $count
        );

        $productTotalPrice = $basketProduct->getPriceAtBills();

        $products = $basketProductRepository->findByUserId($this->authService->getUserId());
        $totalPrice = $this->basketService->calculateTotalPrice($products);

        return json_encode(['success' => true, 'productTotalPrice' => $productTotalPrice, 'totalPrice' => $totalPrice]);
    }

    public function delete(Request $request, BasketProductRepository $basketProductRepository)
    {
        if (!$this->authService->isAuth())
            return json_encode(['success' => false, 'auth' => false]);

        $this->basketService->deleteProductAtBasket($request->get('id'));

        $userId = $this->authService->getUserId();

        $products = $basketProductRepository->findByUserId($userId);
        $totalPrice = $this->basketService->calculateTotalPrice($products);

        $countProductsAtUserBasket = $this->basketService->getCountProductsAtUserBasket($userId);

        return json_encode([
                'success' => true,
                'totalPrice' => $totalPrice,
                'countProductsAtUserBasket' => $countProductsAtUserBasket
        ]);
    }

}
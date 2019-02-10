<?php
/**
 * Created by PhpStorm.
 * User: FoFF
 * Date: 10.02.2019
 * Time: 21:22
 */

namespace Test;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Services\Category\CategoryDefaultService;
use Framework\Config;
use Framework\Database;
use Framework\HTTP\Request;
use PHPUnit\Framework\TestCase;

class CategoryDefaultServiceTest extends TestCase
{
    protected $productRepository;
    protected $categoryDefaultService;
    protected $categoryRepository;
    protected $imageRepository;

    public function setUp()
    {
        Config::init('src/Config/');
        $dataBase = Database::getInstance();
        $dataBase->execute('delete from products;');
        $dataBase->execute('ALTER TABLE products AUTO_INCREMENT = 1;');
        $dataBase->execute('delete from category;');
        $dataBase->execute('ALTER TABLE category AUTO_INCREMENT = 1;');
        $dataBase->execute('delete from images;');
        $dataBase->execute('ALTER TABLE images AUTO_INCREMENT = 1;');

        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->imageRepository = new ImageRepository();
        $this->categoryDefaultService = new CategoryDefaultService($this->productRepository);

        $category = (new Category())
            ->setTitle('test category');

        $savedCategory = $this->categoryRepository->save($category);

        $image = (new Image())
            ->setPath('path')
            ->setAlt('alt');

        $savedImage = $this->imageRepository->save($image);

        $product = (new Product())
            ->setTitle('title')
            ->setDescription('description')
            ->setImage($savedImage)
            ->setCategory($savedCategory)
            ->setAvailability(10)
            ->setPriceAtCoins(100);

        $this->productRepository->save($product);
        $this->productRepository->save($product);
        $this->productRepository->save($product);
    }

    public function testGetProducts()
    {
        Config::set('count_at_page', 3);

        $request = Request::getInstance();
        $request->setGetData([
            'id' => 1
        ]);

        $categoryProducts = $this->categoryDefaultService->getProducts($request, 1);
        $this->assertEquals(3, count($categoryProducts));
    }

    public function testGetProductsWithProductsMoreThatCountAtPage()
    {
        Config::set('count_at_page', 2);

        $request = Request::getInstance();
        $request->setGetData([
            'id' => 1
        ]);

        $categoryProducts = $this->categoryDefaultService->getProducts($request, 1);
        $this->assertEquals(2, count($categoryProducts));

        $categoryProducts = $this->categoryDefaultService->getProducts($request, 2);
        $this->assertEquals(1, count($categoryProducts));
    }

    public function testGetProductsWithInvalidPage()
    {
        Config::set('count_at_page', 3);

        $request = Request::getInstance();
        $request->setGetData([
            'id' => 1
        ]);

        $categoryProducts = $this->categoryDefaultService->getProducts($request, 2);

        $this->assertEquals(0, count($categoryProducts));
    }

    public function testGetPagesCount()
    {
        Config::set('count_at_page', 2);

        $request = Request::getInstance();
        $request->setGetData([
            'id' => 1
        ]);

        $countPages = $this->categoryDefaultService->getPagesCount($request);

        $this->assertEquals(2, $countPages);
    }

    public function testGetPagesCountWithProductsLessThatCountAtPage()
    {
        Config::set('count_at_page', 4);

        $request = Request::getInstance();
        $request->setGetData([
            'id' => 1
        ]);

        $countPages = $this->categoryDefaultService->getPagesCount($request);

        $this->assertEquals(1, $countPages);
    }
}

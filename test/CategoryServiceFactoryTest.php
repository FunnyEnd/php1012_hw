<?php
/**
 * Created by PhpStorm.
 * User: FoFF
 * Date: 10.02.2019
 * Time: 21:52
 */

namespace Test;

use App\Services\Category\CategoryDefaultService;
use App\Services\Category\CategoryFilterService;
use App\Services\Category\CategoryServiceFactory;
use App\Services\FilterService;
use Framework\Dispatcher;
use Framework\HTTP\Request;
use PHPUnit\Framework\TestCase;

class CategoryServiceFactoryTest extends TestCase
{
    protected $categoryServiceFactory;

    public function setUp()
    {
        Dispatcher::addClass(\App\Repository\CharacteristicRepository::class, []);
        Dispatcher::addClass(\App\Repository\ProductCharacteristicsRepository::class, []);
        Dispatcher::addClass(FilterService::class, [
            Dispatcher::get(\App\Repository\CharacteristicRepository::class),
            Dispatcher::get(\App\Repository\ProductCharacteristicsRepository::class)
        ]);
        Dispatcher::addClass(\App\Repository\ProductRepository::class, []);
        Dispatcher::addClass(\App\Services\Category\CategoryDefaultService::class, [
            Dispatcher::get(\App\Repository\ProductRepository::class)
        ]);
        Dispatcher::addClass(\App\Services\Category\CategoryFilterService::class, [
            Dispatcher::get(\App\Repository\ProductCharacteristicsRepository::class),
            Dispatcher::get(\App\Repository\ProductRepository::class),
            Dispatcher::get(\App\Services\FilterService::class)
        ]);

        $this->categoryServiceFactory = new CategoryServiceFactory();
    }

    public function testGetCategoryDefaultService()
    {
        $request = Request::getInstance();
        $service = $this->categoryServiceFactory->getCategoryService($request);

        $this->assertEquals(CategoryDefaultService::class, get_class($service));
    }

    public function testGetCategoryFilterService()
    {
        $request = Request::getInstance();
        $request->setGetData([
            'filter' => '1=12,20'
        ]);

        $service = $this->categoryServiceFactory->getCategoryService($request);

        $this->assertEquals(CategoryFilterService::class, get_class($service));
    }
}
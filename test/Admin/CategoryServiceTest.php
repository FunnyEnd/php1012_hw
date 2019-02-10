<?php

namespace Test\Admin;

use App\Models\Category;
use App\Repository\CategoryRepository;
use App\Services\Admin\CategoryService;
use Framework\Config;
use Framework\Database;
use Framework\HTTP\Request;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    protected $categoryService;
    protected $categoryRepository;

    public function setUp()
    {
        Config::init('src/Config/');
        $dataBase = Database::getInstance();
        $dataBase->execute('delete from products;');
        $dataBase->execute('ALTER TABLE products AUTO_INCREMENT = 1;');
        $dataBase->execute('delete from category;');
        $dataBase->execute('ALTER TABLE category AUTO_INCREMENT = 1;');

        $this->categoryRepository = new CategoryRepository();
        $this->categoryService = new CategoryService($this->categoryRepository);
    }

    public function testStore()
    {
        $request = Request::getInstance();
        $request->setPostData([
            'title' => 'test category'
        ]);

        $this->categoryService->store($request);

        $category = $this->categoryRepository->findById(1);

        $this->assertEquals('test category', $category->getTitle());
    }

    public function testUpdate()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);

        $request = Request::getInstance();
        $request->setPostData([
            'title' => 'test category update'
        ]);

        $request->setGetData([
            'id' => 1
        ]);

        $this->categoryService->update($request);
        $updatedCategory = $this->categoryRepository->findById(1);
        $this->assertEquals('test category update', $updatedCategory->getTitle());
    }

    public function testInvalidUpdate()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);

        $request = Request::getInstance();
        $request->setPostData([
            'title' => 'test category update'
        ]);

        $request->setGetData([
            'id' => 10
        ]);

        $category = $this->categoryService->update($request);

        $this->assertTrue($category->isEmpty());
    }

    public function testDelete()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);

        $request = Request::getInstance();
        $request->setGetData([
            'id' => 1
        ]);

        $this->categoryService->delete($request);

        $count = $this->categoryRepository->findCount();

        $this->assertEquals(0, $count);
    }

    public function testGetCategoryWithValidPage()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);

        Config::set('count_category_at_page', 5);

        $category = $this->categoryService->getCategory(1);
        $this->assertEquals(5, count($category));
    }

    public function testGetCategoryWithInvalidPage()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);

        Config::set('count_category_at_page', 5);

        $category = $this->categoryService->getCategory(-5);
        $this->assertEquals(0, count($category));

        $category = $this->categoryService->getCategory(0);
        $this->assertEquals(0, count($category));

        $category = $this->categoryService->getCategory(10);
        $this->assertEquals(0, count($category));
    }

    public function testGetCountPages(){
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);

        Config::set('count_category_at_page', 2);

        $countPages = $this->categoryService->getCountPages();

        $this->assertEquals(3, $countPages);
    }

}
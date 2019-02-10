<?php

namespace Test;

use App\Models\Category;
use App\Repository\CategoryRepository;
use Framework\Config;
use Framework\Database;
use PHPUnit\Framework\TestCase;

class CategoryRepositoryTest extends TestCase
{
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
    }

    public function testFindById()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);

        $category = $this->categoryRepository->findById(1);

        $this->assertFalse($category->isEmpty());
        $this->assertEquals('test title', $category->getTitle());
        $this->assertEquals(1, $category->getId());
    }

    public function testFindByIdWithInvalidId()
    {
        $category = $this->categoryRepository->findById(10);
        $this->assertTrue($category->isEmpty());
    }

    public function testFindCount()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);

        $count = $this->categoryRepository->findCount();

        $this->assertEquals(2, $count);
    }

    public function testFindAll()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);
        $this->categoryRepository->save($category);

        $category = $this->categoryRepository->findAll();

        $this->assertEquals(2, count($category));
    }

    public function testSave()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);

        $category = $this->categoryRepository->findById(1);

        $this->assertFalse($category->isEmpty());
        $this->assertEquals('test title', $category->getTitle());
        $this->assertEquals(1, $category->getId());
    }

    public function testUpdate()
    {
        $category = (new Category())
            ->setTitle('test title');

        $this->categoryRepository->save($category);

        $category = $this->categoryRepository->findById(1);

        $category->setTitle('test title updated');

        $category = $this->categoryRepository->update($category);

        $this->assertFalse($category->isEmpty());
        $this->assertEquals('test title updated', $category->getTitle());
        $this->assertEquals(1, $category->getId());

        $category = $this->categoryRepository->findById(1);

        $this->assertFalse($category->isEmpty());
        $this->assertEquals('test title updated', $category->getTitle());
        $this->assertEquals(1, $category->getId());
    }

    public function testUpdateInvalid()
    {
        $category = (new Category())
            ->setId(10);

        $category = $this->categoryRepository->update($category);
        $this->assertTrue($category->isEmpty());
    }

    public function testDelete()
    {
        $category = (new Category())
            ->setTitle('test title');

        $category = $this->categoryRepository->save($category);

        $this->categoryRepository->delete($category);
        $count = $this->categoryRepository->findCount();

        $this->assertEquals(0, $count);
    }
}
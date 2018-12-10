<?php
/**
 * Created by PhpStorm.
 * User: phpstudent
 * Date: 10.12.18
 * Time: 18:02
 */

class CategoryRepository
{
    private $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function getById($id){
        $cat_index = array_search(intval($id), array_column($this->category, 'id'));

        if($cat_index === false)
            die("Category with index $id don`t exist!");

        return $this->category[$cat_index];
    }

    public function getAll(){
        return $this->category;
    }

}
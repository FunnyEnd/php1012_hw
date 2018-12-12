<?php
namespace App\Models;

class Category
{
    private $category;

    public function __construct()
    {
        $category = include('list_of_category.php');
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
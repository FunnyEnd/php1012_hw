<?php
namespace App\Models;

class Category
{
    private $category;

    public function __construct()
    {
        $category = include('Data/list_of_category.php');
        $this->category = $category;
    }

    public function getById($id){
        $k = array_search(intval($id), array_column($this->category, 'id'));

        if($k === false) {
            trigger_error('Category with index $id don`t exist!', E_USER_WARNING);
            die("Category with index $id don`t exist!");
        }

        return $this->category[$k];
    }

    public function getAll(){
        return $this->category;
    }

}
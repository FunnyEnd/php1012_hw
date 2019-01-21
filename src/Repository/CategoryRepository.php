<?php

namespace App\Repository;

use App\Models\Category;
use Framework\AbstractRepository;

class CategoryRepository extends AbstractRepository
{
    protected const MODEL_CLASS = Category::class;

    protected const SELECT_BY_ID_SQL = /** @lang text */
            "select id, title, create_at, update_at from category where id = :id";

    protected const SELECT_ALL_SQL = /** @lang text */
            "select id, title, create_at, update_at from category";
}

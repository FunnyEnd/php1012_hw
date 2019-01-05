<?php
return [
        [
                \App\Repository\ProductRepository::class,
                [new \App\Repository\CategoryRepository(), new \App\Repository\ImageRepository()]
        ],
        [\App\Repository\CategoryRepository::class, []],
        [\App\Repository\ImageRepository::class, []]
];
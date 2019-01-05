<?php

namespace App\Repository;

use App\Models\Image;
use DateTime;
use Framework\BaseRepository;

class ImageRepository extends BaseRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "select * from images where id = ?";
    private const SELECT_ALL = /** @lang text */
            "select * from images";

    /**
     * Find image by id
     * @param int $id
     * @return Image
     */
    public function findById(int $id): Image
    {
        $result = $this->db->getOne(self::SELECT_BY_ID, [$id]);
        return $this->mapArrayToImage($result);
    }

    /**
     * Find all Images
     * @return array
     */
    public function findAll(): array
    {
        $result = $this->db->getAll(self::SELECT_ALL, []);
        $products = [];
        foreach ($result as $r)
            array_push($products, $this->mapArrayToImage($r));

        return $products;
    }

    /**
     * Convert array to Image object
     * @param array $row
     * @return Image
     */
    private function mapArrayToImage(array $row): Image
    {
        $image = new Image();
        $row['create_at'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['update_at']);
        $image->formArray($row);
        return $image;
    }
}

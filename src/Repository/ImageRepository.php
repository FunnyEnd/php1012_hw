<?php

namespace App\Repository;

use App\Models\Image;
use DateTime;
use Framework\AbstractModel;
use Framework\AbstractRepository;
use Framework\Constants;

class ImageRepository extends AbstractRepository
{
    private const SELECT_BY_ID = /** @lang text */
            "select * from images where id = ?";
    private const SELECT_ALL = /** @lang text */
            "select * from images";

//    public function findById(int $id): AbstractModel
//    {
//        $result = $this->db->getOne(self::SELECT_BY_ID, [$id]);
//
//        if (empty($result)) {
//            return new Image();
//        }
//
//        return $this->mapFromArray($result);
//    }



    protected function mapFromArray(array $row): Model
    {
        $image = new Image();
        $row['create_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['create_at']);
        $row['update_at'] = DateTime::createFromFormat(Constants::DATETIME_FORMAT, $row['update_at']);
        $image->fromArray($row);
        return $image;
    }
}

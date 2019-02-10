<?php

namespace App\Repository;

use App\Models\Image;
use DateTime;
use Exception;
use Framework\AbstractRepository;
use Framework\Constants;

class ImageRepository extends AbstractRepository
{
    protected const SELECT_ALL_SQL = /** @lang text */
        "select * from images";

    protected const  SELECT_COUNT_SQL = /** @lang text */
        "select count(*) as count from images";

    private const INSERT_SQL = /** @lang text */
        'insert into images (path, alt, create_at, update_at) values (:path, :alt, :create_at, :update_at)';

    public function save(Image $image): Image
    {
        try {
            $currentDateTime = new DateTime();
            $this->db->execute(self::INSERT_SQL, [
                'alt' => $image->getAlt(),
                'path' => $image->getPath(),
                'create_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
                'update_at' => $currentDateTime->format(Constants::DATETIME_FORMAT),
            ]);

            $image->setCreateAt($currentDateTime);
            $image->setUpdateAt($currentDateTime);
            $image->setId($this->db->insertId());

            return $image;

        } catch (Exception $e) {
            $this->logException($e);
        }

        return new Image();
    }


}

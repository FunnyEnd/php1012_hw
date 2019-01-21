<?php

namespace App\Repository;

use App\Models\Characteristic;
use App\Models\Product;
use App\Models\ProductCharacteristic;
use Framework\AbstractModel;
use Framework\AbstractRepository;

class ProductCharacteristicsRepository extends AbstractRepository
{
    protected const MODEL_CLASS = ProductCharacteristic::class;

    protected const SELECT_ALL_SQL = /** @lang MySQL */
            'select p.product_id, p.characteristic_id, ch.title as \'characteristic_title\', ' .
            'p.value, p.create_at, p.update_at ' .
            'from products_characteristics as p ' .
            'left join characteristics as ch on p.characteristic_id = ch.id';

    protected const SELECT_VALUES_BY_CATEGORY_ID_AND_CHAR_ID = /** @lang MySQL */
            'select distinct products_characteristics.value ' .
            'from products_characteristics ' .
            'where products_characteristics.product_id in ( ' .
            'select id ' .
            'from products ' .
            'where products.category_id = :category_id ) ' .
            'and products_characteristics.characteristic_id = :characteristic_id';

    public function findByProductId(int $id): array
    {
        return $this->findAll('p.product_id = :id', [
                'id' => $id
        ]);
    }

    public function findValuesByCategoryIdAndCharId(int $catId, int $charId): array
    {
        $result = $this->db->getAll(self::SELECT_VALUES_BY_CATEGORY_ID_AND_CHAR_ID, [
                'category_id' => $catId,
                'characteristic_id' => $charId
        ]);

        $res = [];
        foreach ($result as $r) {
            array_push($res, $r['value']);
        }

        return $res;
    }

    protected function mapFromArray(array $data): AbstractModel
    {
        $data['product'] = (new Product())->setId($data['product_id']);
        $data['characteristic'] = (new Characteristic())
                ->setId($data['characteristic_id'])
                ->setTitle($data['characteristic_title']);

        return parent::mapFromArray($data);
    }
}
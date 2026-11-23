<?php

require_once __DIR__ . '/BaseDao.php';

class ProductDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('products', [
            'category_id',
            'name',
            'brand',
            'description',
            'price',
            'stock',
        ]);
    }
}

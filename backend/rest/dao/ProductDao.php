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

    public function get_by_category($category_id)
    {
        return $this->query(
            "SELECT * FROM products WHERE category_id = :category_id ORDER BY id",
            ['category_id' => $category_id]
        );
    }
}

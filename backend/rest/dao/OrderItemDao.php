<?php

require_once __DIR__ . '/BaseDao.php';

class OrderItemDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('order_items', [
            'order_id',
            'product_id',
            'size',
            'quantity',
            'unit_price',
        ]);
    }
}

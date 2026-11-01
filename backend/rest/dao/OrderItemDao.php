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

    public function get_by_order($order_id)
    {
        return $this->query(
            "SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id",
            ['order_id' => $order_id]
        );
    }
}

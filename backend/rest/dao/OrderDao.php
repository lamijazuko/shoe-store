<?php

require_once __DIR__ . '/BaseDao.php';

class OrderDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('orders', [
            'user_id',
            'status',
            'total',
        ]);
    }

    public function get_by_user($user_id)
    {
        return $this->query(
            "SELECT * FROM orders WHERE user_id = :user_id ORDER BY id",
            ['user_id' => $user_id]
        );
    }
}

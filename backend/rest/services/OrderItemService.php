<?php

class OrderItemService extends BaseService
{
    public function __construct()
    {
        $this->dao = new OrderItemDao();
    }

    public function get_all()
    {
        return $this->dao->get_all();
    }

    public function get_by_id($id)
    {
        return $this->dao->get_by_id($id);
    }

    public function create($data)
    {
        return $this->dao->add($this->build_entity($data));
    }

    public function update($id, $data)
    {
        if ($this->dao->get_by_id($id) === null) {
            return null;
        }

        $this->dao->update($this->build_entity($data), $id);
        return $this->dao->get_by_id($id);
    }

    public function delete($id)
    {
        if ($this->dao->get_by_id($id) === null) {
            return false;
        }

        return $this->dao->delete($id) > 0;
    }

    private function build_entity($data)
    {
        $this->reject_unknown_fields($data, ['order_id', 'product_id', 'size', 'quantity', 'unit_price']);
        $this->require_fields($data, ['order_id', 'product_id', 'size', 'quantity', 'unit_price']);

        $order_id = $this->integer_value($data, 'order_id', 1);
        $product_id = $this->integer_value($data, 'product_id', 1);

        $this->require_exists(new OrderDao(), $order_id, 'Order');
        $this->require_exists(new ProductDao(), $product_id, 'Product');

        return [
            'order_id' => $order_id,
            'product_id' => $product_id,
            'size' => $this->string_value($data, 'size', 10),
            'quantity' => $this->integer_value($data, 'quantity', 1),
            'unit_price' => $this->number_value($data, 'unit_price', 0),
        ];
    }
}

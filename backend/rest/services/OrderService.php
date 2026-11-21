<?php

class OrderService extends BaseService
{
    private $statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];

    public function __construct()
    {
        $this->dao = new OrderDao();
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
        $this->reject_unknown_fields($data, ['user_id', 'status', 'total']);
        $this->require_fields($data, ['user_id', 'status', 'total']);

        $user_id = $this->integer_value($data, 'user_id', 1);
        $this->require_exists(new UserDao(), $user_id, 'User');

        return [
            'user_id' => $user_id,
            'status' => $this->enum_value($data, 'status', $this->statuses),
            'total' => $this->number_value($data, 'total', 0),
        ];
    }
}

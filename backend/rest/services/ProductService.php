<?php

class ProductService extends BaseService
{
    public function __construct()
    {
        $this->dao = new ProductDao();
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
        $this->reject_unknown_fields($data, ['category_id', 'name', 'brand', 'description', 'price', 'stock']);
        $this->require_fields($data, ['category_id', 'name', 'brand', 'price', 'stock']);

        $category_id = $this->integer_value($data, 'category_id', 1);
        $this->require_exists(new CategoryDao(), $category_id, 'Category');

        $entity = [
            'category_id' => $category_id,
            'name' => $this->string_value($data, 'name', 150),
            'brand' => $this->string_value($data, 'brand', 150),
            'price' => $this->number_value($data, 'price', 0),
            'stock' => $this->integer_value($data, 'stock', 0),
        ];

        if ($this->has($data, 'description')) {
            $entity['description'] = $this->string_value($data, 'description', 1000, false);
        }

        return $entity;
    }
}

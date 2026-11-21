<?php

class CategoryService extends BaseService
{
    public function __construct()
    {
        $this->dao = new CategoryDao();
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
        $entity = $this->build_entity($data);
        if ($this->dao->get_by_name($entity['name']) !== null) {
            throw new InvalidArgumentException('Category name already exists.');
        }

        return $this->dao->add($entity);
    }

    public function update($id, $data)
    {
        if ($this->dao->get_by_id($id) === null) {
            return null;
        }

        $entity = $this->build_entity($data);
        $existing = $this->dao->get_by_name($entity['name']);
        if ($existing !== null && (int) $existing['id'] !== (int) $id) {
            throw new InvalidArgumentException('Category name already exists.');
        }

        $this->dao->update($entity, $id);
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
        $this->reject_unknown_fields($data, ['name']);
        $this->require_fields($data, ['name']);

        return ['name' => $this->string_value($data, 'name', 100)];
    }
}

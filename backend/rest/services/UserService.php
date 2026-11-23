<?php

class UserService extends BaseService
{
    private $roles = ['customer', 'admin'];

    public function __construct()
    {
        $this->dao = new UserDao();
    }

    public function get_all()
    {
        return array_map([$this, 'public_user'], $this->dao->get_all());
    }

    public function get_by_id($id)
    {
        return $this->public_user($this->dao->get_by_id($id));
    }

    public function public_user($user)
    {
        if ($user === null) {
            return null;
        }

        unset($user['password_hash']);
        return $user;
    }

    public function create($data)
    {
        $entity = $this->build_entity($data);
        $this->assert_email_available($entity['email']);
        return $this->public_user($this->dao->add($entity));
    }

    public function update($id, $data)
    {
        if ($this->dao->get_by_id($id) === null) {
            return null;
        }

        $entity = $this->build_entity($data);
        $this->assert_email_available($entity['email'], $id);

        $this->dao->update($entity, $id);
        return $this->public_user($this->dao->get_by_id($id));
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
        $this->reject_unknown_fields($data, ['first_name', 'last_name', 'email', 'password_hash', 'phone', 'role']);
        $this->require_fields($data, ['first_name', 'last_name', 'email', 'password_hash', 'role']);

        $entity = [
            'first_name' => $this->string_value($data, 'first_name', 100),
            'last_name' => $this->string_value($data, 'last_name', 100),
            'email' => $this->email_value($data, 'email'),
            'password_hash' => $this->string_value($data, 'password_hash', 255),
            'role' => $this->enum_value($data, 'role', $this->roles),
        ];

        if ($this->has($data, 'phone') && $data['phone'] !== null && $data['phone'] !== '') {
            $entity['phone'] = $this->string_value($data, 'phone', 50, false);
        }

        return $entity;
    }

    private function assert_email_available($email, $exclude_id = null)
    {
        $existing = $this->dao->get_by_email($email);
        if ($existing === null) {
            return;
        }

        if ($exclude_id !== null && (int) $existing['id'] === (int) $exclude_id) {
            return;
        }

        throw new InvalidArgumentException('Email already exists.');
    }
}

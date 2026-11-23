<?php

class BaseService
{
    protected $dao;

    protected function reject_unknown_fields($data, $allowed)
    {
        foreach (array_keys($data) as $field) {
            if (!in_array($field, $allowed, true)) {
                throw new InvalidArgumentException("Unknown field: {$field}.");
            }
        }
    }

    protected function require_fields($data, $fields)
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === null || $data[$field] === '') {
                throw new InvalidArgumentException("{$field} is required.");
            }
        }
    }

    protected function has($data, $field)
    {
        return array_key_exists($field, $data);
    }

    protected function string_value($data, $field, $max_length = 255, $required = true)
    {
        if (!$this->has($data, $field)) {
            if ($required) {
                throw new InvalidArgumentException("{$field} is required.");
            }
            return null;
        }

        $value = $data[$field];
        if (!is_string($value) || trim($value) === '' || strlen($value) > $max_length) {
            throw new InvalidArgumentException("{$field} must be a non-empty string of at most {$max_length} characters.");
        }

        return trim($value);
    }

    protected function number_value($data, $field, $min = 0, $required = true)
    {
        if (!$this->has($data, $field)) {
            if ($required) {
                throw new InvalidArgumentException("{$field} is required.");
            }
            return null;
        }

        $value = $data[$field];
        if (!is_numeric($value) || (float) $value < $min) {
            throw new InvalidArgumentException("{$field} must be a number greater than or equal to {$min}.");
        }

        return (float) $value;
    }

    protected function integer_value($data, $field, $min = 0, $required = true)
    {
        if (!$this->has($data, $field)) {
            if ($required) {
                throw new InvalidArgumentException("{$field} is required.");
            }
            return null;
        }

        $value = filter_var($data[$field], FILTER_VALIDATE_INT);
        if ($value === false || $value < $min) {
            throw new InvalidArgumentException("{$field} must be an integer greater than or equal to {$min}.");
        }

        return $value;
    }

    protected function email_value($data, $field, $required = true)
    {
        $value = $this->string_value($data, $field, 255, $required);
        if ($value === null) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException("{$field} must be a valid email address.");
        }

        return strtolower($value);
    }

    protected function enum_value($data, $field, $allowed, $required = true)
    {
        if (!$this->has($data, $field)) {
            if ($required) {
                throw new InvalidArgumentException("{$field} is required.");
            }
            return null;
        }

        $value = $data[$field];
        if (!in_array($value, $allowed, true)) {
            throw new InvalidArgumentException("{$field} must be one of: " . implode(', ', $allowed) . '.');
        }

        return $value;
    }

    protected function require_exists($dao, $id, $label)
    {
        if ($dao->get_by_id($id) === null) {
            throw new InvalidArgumentException("{$label} not found.");
        }
    }
}

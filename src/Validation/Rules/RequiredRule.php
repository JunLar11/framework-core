<?php

namespace Chomsky\Validation\Rules;

class RequiredRule implements ValidationRule
{
    public function message(): string
    {
        return "The :field field is required";
    }
    public function isValid($field, $data): bool
    {
        if (!array_key_exists($field, $data)) {
            return false;
        }
        return isset($data[$field]) && $data[$field] !== "";
    }
}

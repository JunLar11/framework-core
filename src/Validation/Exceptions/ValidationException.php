<?php

namespace Chomsky\Validation\Exceptions;

use Chomsky\Exceptions\ChomskiException;
use Chomsky\Exceptions\ChomskyException;

class ValidationException extends ChomskyException
{
    /**
     * @var array
     */
    private $errors = [];
    public function __construct(array $errors)
    {
        $this->errors=$errors;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}

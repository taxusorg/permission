<?php

namespace Taxusorg\Permission\Exceptions;

use Throwable;
use Exception;

class RoleDuplication extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('The target role already exists. ' . $message, $code, $previous);
    }
}

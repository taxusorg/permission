<?php

namespace Taxusorg\Permission\Exceptions;

use Exception;
use Throwable;

class TypeError extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Param type error. ' . $message, $code, $previous);
    }
}

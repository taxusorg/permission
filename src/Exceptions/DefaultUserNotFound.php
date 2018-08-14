<?php

namespace Taxusorg\Permission\Exceptions;

use Exception;
use Throwable;

class DefaultUserNotFound extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Default User Not Found. ' . $message, $code, $previous);
    }
}

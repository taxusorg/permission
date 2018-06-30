<?php

namespace Taxusorg\Permission\Exceptions;

use Throwable;

class AccessDeniedException extends \Exception
{
    public function __construct($message = "", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

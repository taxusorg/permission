<?php

namespace Taxusorg\Permission\Exceptions;

use Error;
use Throwable;

class FrameworkError extends Error {

    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

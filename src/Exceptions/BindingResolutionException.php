<?php

namespace Elphis\Exceptions;

use Psr\Container\NotFoundExceptionInterface;
use Exception;

class BindingResolutionException extends Exception implements NotFoundExceptionInterface
{
    protected $message = '%s could not be resolved';
    protected $code = 500;

    public function __construct($binding, $code)
    {
        $message = sprintf($this->message, $binding);

        parent::__construct($message, $code);
    }
}

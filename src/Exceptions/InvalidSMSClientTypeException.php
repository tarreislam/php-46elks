<?php


namespace Tarre\Php46Elks\Exceptions;


use Exception;
use Throwable;

class InvalidSMSClientTypeException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Invalid type "%s"', $message);

        parent::__construct($message, $code, $previous);
    }
}

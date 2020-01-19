<?php


namespace Tarre\Php46Elks\Exceptions;


use Exception;
use Throwable;

class NoRecipientsSetException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf('At least 1 recipient is required');

        parent::__construct($message, $code, $previous);
    }
}

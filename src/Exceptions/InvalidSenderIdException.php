<?php


namespace Tarre\Php46Elks\Exceptions;


use Exception;
use Throwable;

class InvalidSenderIdException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Invalid SenderId "%s" read more here: https://46elks.se/kb/text-sender-id', $message);

        parent::__construct($message, $code, $previous);
    }

}

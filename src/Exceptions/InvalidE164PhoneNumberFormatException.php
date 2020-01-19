<?php


namespace Tarre\Php46Elks\Exceptions;


use Exception;
use Throwable;

class InvalidE164PhoneNumberFormatException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Invalid phone number format "%s" read more here: https://46elks.se/kb/e164', $message);

        parent::__construct($message, $code, $previous);
    }
}

<?php


namespace Tarre\Php46Elks\Utils;


use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;

class Validator
{

    /**
     * @param $phoneNumber
     * @throws InvalidE164PhoneNumberFormatException
     * @return void
     */
    public static function validateE164PhoneNumber($phoneNumber)
    {
        if (!preg_match('/^\+\d{1,3}\d+/', $phoneNumber)) {
            throw new InvalidE164PhoneNumberFormatException($phoneNumber);
        }

    }

}

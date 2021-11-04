<?php

namespace Tarre\Php46Elks\Elks\Sms\Responses;

use Tarre\Php46Elks\ConstructSetterFactory;
use Tarre\Php46Elks\Elks\Exceptions\InvalidNumberToForwardToException;
use Tarre\Php46Elks\Elks\Sms\traits\CreatedTrait;
use Tarre\Php46Elks\Elks\Sms\traits\DirectionTrait;
use Tarre\Php46Elks\Elks\Sms\traits\FromTrait;
use Tarre\Php46Elks\Elks\Sms\traits\IdTrait;
use Tarre\Php46Elks\Elks\Sms\traits\MessageTrait;
use Tarre\Php46Elks\Elks\Sms\traits\ToTrait;
use Tarre\Php46Elks\ValidatorHelper;

class ReceivedSmsResponse extends ConstructSetterFactory
{
    use IdTrait, FromTrait, ToTrait, MessageTrait, DirectionTrait, CreatedTrait;

    /**
     * Reply to the message
     * @param string $message
     * @return array
     */
    public function reply(string $message)
    {
        return ['reply' => $message];
    }


    /**
     * forward sets the phone number you want to forward incomming messages to.
     * prefix and suffix variables can optionally be used to add text to the beginning or the end of the message.
     * @param string $to
     * @param string|null $prefix
     * @param string|null $suffix
     * @return string[]
     * @throws InvalidNumberToForwardToException
     */
    public function forward(string $to, string $prefix = null, string $suffix = null): array
    {
        if (!ValidatorHelper::isValidE164PhoneNubmer($to)) {
            throw new InvalidNumberToForwardToException($to);
        }
        $res = ['forward' => $to];

        if (!is_null($prefix)) {
            $res['prefix'] = $prefix;
        }

        if (!is_null($suffix)) {
            $res['prefix'] = $suffix;
        }

        return $res;
    }

}

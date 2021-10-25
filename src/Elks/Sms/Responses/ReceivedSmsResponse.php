<?php

namespace Tarre\Php46Elks\Elks\Sms\Responses;

use Tarre\Php46Elks\ConstructSetterFactory;
use Tarre\Php46Elks\Elks\Exceptions\InvalidNumberToForwardToException;
use Tarre\Php46Elks\ValidatorHelper;

class ReceivedSmsResponse extends ConstructSetterFactory
{
    const DIRECTION_INCOMING = 'incoming';

    protected string $id;
    protected string $from;
    protected string $to;
    protected string $message;
    protected string $direction;
    protected string $created;

    /**
     * The unique id of the message in our systems.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * The sender of the SMS.
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * The phone number the SMS was sent to.
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * The contents of the SMS.
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * The direction of the SMS. Always ”incoming” for incoming SMS.
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * The time in UTC when the SMS object was created in our systems.
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

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
    public function forward(string $to, string $prefix = null, string $suffix = null)
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

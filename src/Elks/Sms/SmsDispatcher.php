<?php

namespace Tarre\Php46Elks\Elks\Sms;


use Tarre\Php46Elks\Elks\Sms\Resources\SmsMessage;
use Tarre\Php46Elks\SenderFactory;

class SmsDispatcher extends SenderFactory
{
    protected array $messages = [];

    /**
     * @param SmsMessage $smsMessage
     * @return SmsDispatcher
     */
    public function addMessage(SmsMessage $smsMessage)
    {
        $this->messages[] = $smsMessage;
        return $this;
    }

    /**
     * @param array $array
     * @return SmsDispatcher
     */
    public function addManyMessages(array $array)
    {
        foreach ($array as $smsMessage) {
            $this->addMessage($smsMessage);
        }
        return $this;
    }

    public function uri(): string
    {
        return 'sms';
    }

    public function method(): string
    {
        return 'post';
    }
}

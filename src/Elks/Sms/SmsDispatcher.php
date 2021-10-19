<?php

namespace Tarre\Php46Elks\Elks\Sms;


use Tarre\Php46Elks\Elks\Sms\Responses\ReceivedSmsMessage;
use Tarre\Php46Elks\SenderFactory;

class SmsDispatcher extends SenderFactory
{
    protected array $messages = [];


    public function uri(): string
    {
        return 'sms';
    }

    public function method(): string
    {
        return SenderFactory::METHOD_POST;
    }

    protected function mapResponse(): string
    {
        return ReceivedSmsMessage::class;
    }
}

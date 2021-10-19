<?php

namespace Tarre\Php46Elks\Elks\Sms;


use Tarre\Php46Elks\Elks\Sms\Responses\ReceivedMmsMessage;
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

    protected function mapResult(array $result)
    {
        /*
         * Flatten reqs
         */
        $reqs = array_merge(...$result);
        /*
         * Map result
         */
        return array_map(fn($reqs) => new ReceivedMmsMessage($reqs), $reqs);
    }

}

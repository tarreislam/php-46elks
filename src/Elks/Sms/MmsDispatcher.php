<?php

namespace Tarre\Php46Elks\Elks\Sms;

use Tarre\Php46Elks\Elks\Sms\Responses\ReceivedMmsMessage;

class MmsDispatcher extends SmsDispatcher
{
    public function uri(): string
    {
        return 'mms';
    }

    protected function mapResponse(): string
    {
        return ReceivedMmsMessage::class;
    }
}

<?php

namespace Tarre\Php46Elks\Elks\Mms;

use Tarre\Php46Elks\Elks\Mms\Responses\ReceivedMmsResponse;
use Tarre\Php46Elks\Elks\Sms\SmsDispatcher;

class MmsDispatcher extends SmsDispatcher
{
    public function uri(): string
    {
        return 'mms';
    }

    protected function mapResponse(): string
    {
        return ReceivedMmsResponse::class;
    }
}

<?php

namespace Tarre\Php46Elks\Elks\Sms;

class MmsDispatcher extends SmsDispatcher
{
    public function uri(): string
    {
        return 'mms';
    }
}

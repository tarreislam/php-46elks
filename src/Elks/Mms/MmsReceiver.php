<?php

namespace Tarre\Php46Elks\Elks\Mms;

use Tarre\Php46Elks\Elks\Mms\Responses\ReceivedMmsMessage;
use Tarre\Php46Elks\ReceiverFactory;

class MmsReceiver extends ReceiverFactory
{
    /**
     * @param callable $cb
     * @return mixed
     */
    public function mms(callable $cb)
    {
        return $this->cbUsingCallable($cb, ReceivedMmsMessage::class);
    }

}

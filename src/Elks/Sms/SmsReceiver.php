<?php

namespace Tarre\Php46Elks\Elks\Sms;

use Tarre\Php46Elks\Elks\Sms\Responses\ReceivedSmsMessage;
use Tarre\Php46Elks\Elks\Sms\Responses\SMSDeliveryReport;
use Tarre\Php46Elks\ReceiverFactory;

class SmsReceiver extends ReceiverFactory
{
    /**
     * Handle received sms message. The "ReceivedSmsMessage" class is passed as the first parameter
     * @param callable $cb
     * @return mixed
     */
    public function sms(callable $cb)
    {
        return $this->cbUsingCallable($cb, ReceivedSmsMessage::class);
    }

    /**
     * @param callable $cb
     * @return mixed
     */
    public function dlr(callable $cb)
    {
        return $this->cbUsingCallable($cb, SMSDeliveryReport::class);
    }


}

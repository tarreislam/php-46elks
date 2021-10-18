<?php

namespace Tarre\Php46Elks\Elks\Sms;

use Tarre\Php46Elks\Elks\Sms\Responses\ReceivedSmsMessage;
use Tarre\Php46Elks\Elks\Sms\Responses\SMSDeliveryReport;

class SmsReceiver
{
    protected array $params;

    public function __construct(array $queryParams, array $postParams = null)
    {
        if (is_null($postParams)) {
            $params = $queryParams;
        } else {
            $params = array_merge($queryParams, $postParams);
            $params = array_unique($params);
        }

        $this->params = $params;
    }

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

    protected function cbUsingCallable(callable $cb, string $class)
    {
        $res = new $class($this->params);
        return $cb($res);
    }

}

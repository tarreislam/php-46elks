<?php

namespace Tarre\Php46Elks\Elks\Sms;


use Tarre\Php46Elks\Elks\Sms\Models\SmsMessage as SmsMessageModel;
use Tarre\Php46Elks\Elks\Sms\Responses\SmsMessage as SmsMessageResponse;
use Tarre\Php46Elks\SenderFactory;

class SmsDispatcher extends SenderFactory
{
    protected array $messages = [];

    /**
     * @param SmsMessageModel $smsMessage
     * @return SmsDispatcher
     */
    public function addMessage(SmsMessageModel $smsMessage)
    {
        $this->messages[] = $smsMessage;
        return $this;
    }

    /**
     * @param array $array
     * @return SmsDispatcher
     */
    public function setMessages(array $array)
    {
        $this->messages = $array;
        return $this;
    }

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
        return array_map(fn($res) => new SmsMessageResponse($res), $result);
    }

    protected function getQueryBuilderFactories(): array
    {
        return $this->messages;
    }
}

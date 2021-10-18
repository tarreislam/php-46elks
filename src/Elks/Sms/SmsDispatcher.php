<?php

namespace Tarre\Php46Elks\Elks\Sms;


use Tarre\Php46Elks\Elks\Sms\Requests\SmsMessage as SmsMessageRequest;
use Tarre\Php46Elks\Elks\Sms\Responses\SentSmsMessage as SmsMessageResponse;
use Tarre\Php46Elks\SenderFactory;

class SmsDispatcher extends SenderFactory
{
    protected array $messages = [];

    /**
     * @param SmsMessageRequest $smsMessage
     * @return SmsDispatcher
     */
    public function addMessage(SmsMessageRequest $smsMessage)
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
        /*
         * Flatten reqs
         */
        $reqs = array_merge(...$result);
        /*
         * Map result
         */
        return array_map(fn($reqs) => new SmsMessageResponse($reqs), $reqs);
    }

    protected function getQueryBuilderFactories(): array
    {
        return $this->messages;
    }
}

<?php

namespace Tarre\Php46Elks\Elks\Sms;

use Tarre\Php46Elks\Elks\Sms\Requests\SmsHistoryRequest;
use Tarre\Php46Elks\Elks\Sms\Responses\SmsHistoryResponse;
use Tarre\Php46Elks\Interfaces\QueryInterface;
use Tarre\Php46Elks\SenderFactory;
use Tarre\Php46Elks\Traits\QueryBuilder;

class SmsHistory extends SenderFactory implements QueryInterface
{
    use QueryBuilder;
    /**@var string|int $id * */
    protected $id;

    /**
     * @param string|int $id
     * @return SmsHistory
     */
    public function whereId($id)
    {
        $this->id = $id;
        // add bogus request
        $this->addRequest(new SmsHistoryRequest);
        return $this;
    }

    protected function uri(): string
    {
        return "sms/{$this->id}";
    }

    protected function method(): string
    {
        return SenderFactory::METHOD_GET;
    }

    protected function mapResponse(): string
    {
        return SmsHistoryResponse::class;
    }

}

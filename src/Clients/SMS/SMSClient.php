<?php

namespace Tarre\Php46Elks\Clients\SMS;

use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Clients\SMS\Services\SMSDispatcherService;
use Tarre\Php46Elks\Clients\SMS\Services\SMSDLRService;
use Tarre\Php46Elks\Clients\SMS\Services\SMSHistoryService;
use Tarre\Php46Elks\Clients\SMS\Services\SMSReceiverService;
use Tarre\Php46Elks\Clients\SMS\Traits\SenderTrait;
use Tarre\Php46Elks\Exceptions\InvalidSMSClientTypeException;
use Tarre\Php46Elks\Traits\QueryOptionTrait;

class SMSClient extends BaseClient
{
    use SenderTrait, QueryOptionTrait;


    /**
     * type can be set to "sms" or "mms"
     * @param string $type
     * @return SMSDispatcherService
     * @throws InvalidSMSClientTypeException
     */
    public function dispatcher($type = 'sms'): SMSDispatcherService
    {
        return new SMSDispatcherService($this, $type);
    }

    /**
     * @return SMSDispatcherService
     * @throws InvalidSMSClientTypeException
     */
    public function SMSDispatcher(): SMSDispatcherService
    {
        return $this->dispatcher('sms');
    }

    /**
     * @return SMSDispatcherService
     * @throws InvalidSMSClientTypeException
     */
    public function MMSDispatcher(): SMSDispatcherService
    {
        return $this->dispatcher('mms');
    }

    /**
     * @return SMSReceiverService
     */
    public function receiver(): SMSReceiverService
    {
        return new SMSReceiverService;
    }

    /**
     * @return SMSDLRService
     */
    public function dlr(): SMSDLRService
    {
        return new SMSDLRService;
    }

    /**
     * @param string $type
     * @return SMSHistoryService
     * @throws InvalidSMSClientTypeException
     */
    public function history($type = 'sms'): SMSHistoryService
    {
        return new SMSHistoryService($this, $type);
    }

}

<?php


namespace Tarre\Php46Elks\Clients\PhoneCall;


use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallRouterService;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallDispatcherService;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallHistoryService;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;
use Tarre\Php46Elks\Clients\PhoneCall\Traits\CommonPhoneTraits;
use Tarre\Php46Elks\Traits\QueryOptionTrait;

class PhoneCallClient extends BaseClient
{
    use QueryOptionTrait, CommonPhoneTraits;

    /**
     * @return PhoneCallDispatcherService
     */
    public function dispatcher()
    {
        return new PhoneCallDispatcherService($this);
    }

    /**
     * @return PhoneCallReceiverService
     */
    public function receiver()
    {
        return new PhoneCallReceiverService;
    }

    /**
     * @return PhoneCallRouterService
     */
    public function router(): PhoneCallRouterService
    {
        return new PhoneCallRouterService;
    }

    /**
     * @return PhoneCallHistoryService
     */
    public function history()
    {
        return new PhoneCallHistoryService($this);
    }


}

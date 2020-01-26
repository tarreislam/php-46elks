<?php


namespace Tarre\Php46Elks\Clients\PhoneCall;


use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallActionRouter;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallDispatcherService;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;
use Tarre\Php46Elks\Clients\PhoneCall\Traits\CommonPhoneTraits;

class PhoneCallClient extends BaseClient
{
    use CommonPhoneTraits;

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
     * @param null $baseUrl
     * @return PhoneCallActionRouter
     */
    public function router($baseUrl = null): PhoneCallActionRouter
    {
        return new PhoneCallActionRouter($baseUrl);
    }

    // TODO Make a phone call

    // TODO CALL HISTORY

    // TODO GET CALL BY ID

    // TODO CALL FROM CLIENT?
}

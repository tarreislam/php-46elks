<?php


namespace Tarre\Php46Elks\Clients\PhoneCall;


use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverRouter;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

class PhoneCallClient extends BaseClient
{
    /**
     * @return PhoneCallReceiverService
     */
    public function receiver()
    {
        return new PhoneCallReceiverService;
    }

    /**
     * @return PhoneCallReceiverRouter
     */
    public function router($baseUrl): PhoneCallReceiverRouter
    {
        return new PhoneCallReceiverRouter($baseUrl);
    }

    // TODO Make a phone call

    // TODO CALL HISTORY

    // TODO GET CALL BY ID

    // TODO CALL FROM CLIENT?
}

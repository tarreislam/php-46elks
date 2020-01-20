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
    public function router(): PhoneCallReceiverRouter
    {
        return new PhoneCallReceiverRouter;
    }
}

<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Services;


use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Interfaces\ReceiverInterface;

class PhoneCallReceiverService implements ReceiverInterface
{
    /**
     * @param callable $fnc
     * @param array|null $request
     * @return mixed
     */
    public function handleRequest(callable $fnc, array $request = null)
    {

        if (is_null($request)) {
            $request = $_REQUEST;
        }

        return $fnc(new ReceivedPhoneCall($request));
    }
}

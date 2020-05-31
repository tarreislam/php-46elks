<?php


namespace Tarre\Php46Elks\Clients\SMS\Services;

use Tarre\Php46Elks\Clients\SMS\Resources\Message;
use Tarre\Php46Elks\Interfaces\ReceiverInterface;

class SMSReceiverService implements ReceiverInterface
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

        return $fnc(new Message($request));
    }

}

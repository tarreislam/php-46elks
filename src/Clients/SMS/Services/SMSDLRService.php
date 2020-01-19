<?php


namespace Tarre\Php46Elks\Clients\SMS\Services;

use Tarre\Php46Elks\Clients\SMS\Resources\DeliveryReportResource;
use Tarre\Php46Elks\Interfaces\ReceiverInterface;


class SMSDLRService implements ReceiverInterface
{
    /**
     * @param callable $fn
     * @param array|null $request
     * @return mixed
     */
    public function handleRequest(callable $fn, array $request = null)
    {

        if (is_null($request)) {
            $request = $_REQUEST;
        }

        return $fn(new DeliveryReportResource($request));
    }
}

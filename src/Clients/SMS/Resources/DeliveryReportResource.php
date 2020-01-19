<?php

namespace Tarre\Php46Elks\Clients\SMS\Resources;


class DeliveryReportResource
{
    protected $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * The unique id of the message in our systems.
     * @return mixed
     */
    public function id()
    {
        return $this->request['id'];
    }


    /**
     * Either ”delivered” or ”failed”.
     * @return mixed
     */
    public function status()
    {
        return $this->request['status'];
    }

    /**
     * The delivery time in UTC. Only included if status is set to delivered.
     * @return mixed
     */
    public function delivered()
    {
        return $this->request['delivered'];
    }
}

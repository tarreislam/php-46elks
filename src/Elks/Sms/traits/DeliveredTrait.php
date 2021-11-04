<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait DeliveredTrait
{
    protected string $delivered;

    /**
     * The delivery time in UTC. Only included if status is set to delivered.
     * @return string
     */
    public function getDelivered(): string
    {
        return $this->delivered;
    }
}

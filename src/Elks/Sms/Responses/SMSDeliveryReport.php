<?php

namespace Tarre\Php46Elks\Elks\Sms\Responses;

use Tarre\Php46Elks\ConstructSetter;

class SMSDeliveryReport extends ConstructSetter
{
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';

    protected string $id;
    protected string $status;
    protected string $delivered;

    /**
     * The unique id of the message in our systems.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Either ”delivered” or ”failed”.
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * The delivery time in UTC. Only included if status is set to delivered.
     * @return string
     */
    public function getDelivered(): string
    {
        return $this->delivered;
    }
}

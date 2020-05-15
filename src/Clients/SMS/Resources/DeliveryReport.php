<?php

namespace Tarre\Php46Elks\Clients\SMS\Resources;


use Tarre\Php46Elks\Interfaces\Arrayable;

class DeliveryReport implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * The unique id of the message in our systems.
     * @return string|null
     */
    public function id()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }


    /**
     * Either ”delivered” or ”failed”.
     * @return string|null
     */
    public function status()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * The delivery time in UTC. Only included if status is set to delivered.
     * @return string|null
     */
    public function delivered()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->data;
    }
}

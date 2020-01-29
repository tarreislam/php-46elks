<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Resources;

use Tarre\Php46Elks\Clients\PhoneCall\PhoneCallClient;

/**
 * @property PhoneCallClient phoneCallClient
 */
class CallHistory
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * @return string
     */
    public function direction(): string
    {
        return $this->data[__METHOD__];
    }

    /**
     * @return string
     */
    public function from(): string
    {
        return $this->data[__METHOD__];
    }


    /**
     * @return string
     */
    public function to(): string
    {
        return $this->data[__METHOD__];
    }

    /**
     * @return string
     */
    public function created(): string
    {
        return $this->data[__METHOD__];
    }


    /**
     * @return array
     */
    public function actions(): array
    {
        return $this->data[__METHOD__];
    }


    /**
     * @return array
     */
    public function start(): string
    {
        return $this->data[__METHOD__];
    }


    /**
     * @return string
     */
    public function state(): string
    {
        return $this->data[__METHOD__];
    }


    /**
     * @return int
     */
    public function cost(): int
    {
        return $this->data[__METHOD__];
    }


    /**
     * @return int
     */
    public function duration(): int
    {
        return $this->data[__METHOD__];
    }


    /**
     * @return array
     */
    public function legs(): array
    {
        return $this->data[__METHOD__];
    }


    /**
     * @return string
     */
    public function id(): string
    {
        return $this->data[__METHOD__];
    }
}

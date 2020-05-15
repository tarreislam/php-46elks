<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Resources;

use Tarre\Php46Elks\Clients\PhoneCall\PhoneCallClient;
use Tarre\Php46Elks\Interfaces\Arrayable;

/**
 * @property PhoneCallClient phoneCallClient
 */
class CallHistory implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * @return string|null
     */
    public function direction()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * @return string|null
     */
    public function from()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }


    /**
     * @return string|null
     */
    public function to()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * @return string|null
     */
    public function created()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }


    /**
     * @return array
     */
    public function actions(): array
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : [];
    }


    /**
     * @return string|null
     */
    public function start()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }


    /**
     * @return string|null
     */
    public function state()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }


    /**
     * @return int
     */
    public function cost(): int
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : 0;
    }


    /**
     * @return int
     */
    public function duration(): int
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : 0;
    }


    /**
     * @return array
     */
    public function legs(): array
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : [];
    }


    /**
     * @return string|null
     */
    public function id()
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

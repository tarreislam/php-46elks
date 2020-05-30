<?php


namespace Tarre\Php46Elks\Clients\Number\Resources;


use Tarre\Php46Elks\Interfaces\Arrayable;

class Number implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function id()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * @return string|null
     */
    public function active()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * @return string|null
     */
    public function country()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * @return string|null
     */
    public function number()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * @return array
     */
    public function capabilities()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : [];
    }

    /**
     * @return string|null
     */
    public function deallocated()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}

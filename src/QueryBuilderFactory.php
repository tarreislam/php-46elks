<?php

namespace Tarre\Php46Elks;

abstract class QueryBuilderFactory
{
    protected array $data;

    /**
     * @param string $key
     * @param $v
     */
    public function set(string $key, $v)
    {
        $this->data[$key] = $v;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Validate the resource and throw exceptions
     * @return void
     * @throws \Exception
     */
    public abstract function validate(): void;

    /**
     * Build the resource
     * @return void
     */
    public abstract function build(): void;

}

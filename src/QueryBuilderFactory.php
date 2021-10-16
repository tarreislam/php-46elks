<?php

namespace Tarre\Php46Elks;

abstract class QueryBuilderFactory
{
    protected array $data;

    /**
     * @return bool
     */
    public abstract function validate(): bool;

    /**
     * @param string $key
     * @param $v
     */
    protected function set(string $key, $v)
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
}

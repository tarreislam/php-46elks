<?php

namespace Tarre\Php46Elks;


use Tarre\Php46Elks\Interfaces\QueryBuilder;

abstract class QueryBuilderFactory implements QueryBuilder
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
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return false|string
     */
    public function toJson(): string
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

<?php

namespace Tarre\Php46Elks;

abstract class ReceiverFactory
{
    protected array $params;

    public function __construct(array $queryParams, array $postParams = null)
    {
        if (is_null($postParams)) {
            $params = $queryParams;
        } else {
            $params = array_merge($queryParams, $postParams);
            $params = array_unique($params);
        }

        $this->params = $params;
    }

    protected function cbUsingCallable(callable $cb, string $class)
    {
        $res = new $class($this->params);
        return $cb($res);
    }
}

<?php

namespace Tarre\Php46Elks\Interfaces;

interface ReceiverInterface
{
    /**
     * @param callable $fnc
     * @param array|null $request
     * @return mixed
     */
    public function handleRequest(callable $fnc, array $request = null);
}

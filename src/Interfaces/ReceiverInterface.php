<?php


namespace Tarre\Php46Elks\Interfaces;


interface ReceiverInterface
{
    public function handleRequest(callable $fn, array $request = null);
}

<?php


namespace Tarre\Php46Elks\Interfaces;


interface RequestStructureInterface
{
    /**
     * The array of array requests that should be used with guzzle
     * @return array
     */
    public function getRequests(): array;
}

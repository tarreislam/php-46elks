<?php

namespace Tarre\Php46Elks\Interfaces;

interface SenderInterface
{
    /**
     * Get collection of requests to process by send
     *
     * @return array
     */
    public function getRequests(): array;

    /**
     * Send requests
     *
     * @return array
     */
    public function send(): array;
}

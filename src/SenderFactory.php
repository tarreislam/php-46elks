<?php

namespace Tarre\Php46Elks;

use Tarre\Php46Elks\Client\Client;

abstract class SenderFactory
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param QueryBuilderFactory $qbFactory
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(QueryBuilderFactory $qbFactory)
    {
        /*
         * Validate
         */
        $qbFactory->validate();
        /*
         * Make request
         */
        $res = $this->client->request($this->method(), $this->uri(), $qbFactory->toArray());
        /*
         * Return result as array
         */
        return $res->getBody()->getContents();
    }


    public abstract function uri(): string;

    public abstract function method(): string;

    public abstract function handleResource();

}

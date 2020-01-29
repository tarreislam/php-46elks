<?php


namespace Tarre\Php46Elks\Clients;


use GuzzleHttp\Client;

/**
 * @property Client guzzleClient
 */
class BaseClient
{
    protected $guzzleClient;

    /**
     * BaseClient constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->guzzleClient = $client;
    }

    /**
     * @return Client
     */
    public function getGuzzleClient(): Client
    {
        return $this->guzzleClient;
    }
}

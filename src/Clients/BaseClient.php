<?php


namespace Tarre\Php46Elks\Clients;


use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions as GuzzleHttpRequestOptions;

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

    /**
     * @return string
     */
    public function getAuthUsername(): string
    {
        return $this->guzzleClient->getConfig(GuzzleHttpRequestOptions::AUTH)[0];
    }

    /**
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->guzzleClient->getConfig(GuzzleHttpRequestOptions::AUTH)[1];
    }
}

<?php


namespace Tarre\Php46Elks\Clients;


use GuzzleHttp\Client;

/**
 * @property Client guzzleClient
 */
class BaseClient
{
    protected $guzzleClient;
    protected $dryRun;

    /**
     * BaseClient constructor.
     * @param Client $client
     * @param $dryRun
     */
    public function __construct(Client $client, $dryRun)
    {
        $this->guzzleClient = $client;
        $this->dryRun = $dryRun;
    }

    /**
     * @return Client
     */
    public function getGuzzleClient(): Client
    {
        return $this->guzzleClient;
    }

    /**
     * @return bool
     */
    public function dryRun(): bool
    {
        return $this->dryRun;
    }

}

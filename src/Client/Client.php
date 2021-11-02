<?php

namespace Tarre\Php46Elks\Client;

use GuzzleHttp\Client as GuzzleClient;
use Tarre\Php46Elks\Credentials\Credential;

class Client
{
    protected GuzzleClient $guzzleClient;

    /**
     * @param Credential $credential
     */
    public function __construct(Credential $credential)
    {
        /*
         * Create guzzle client instnace
         */
        $client = new GuzzleClient([
            'base_uri' => $credential->getEndpoint(),
            'auth' => [
                $credential->getUsername(),
                $credential->getPassword()
            ]
        ]);
        /*
         * Set client
         */
        $this->setGuzzleClient($client);
    }

    /**
     * @return GuzzleClient
     */
    public function getGuzzleClient(): GuzzleClient
    {
        return $this->guzzleClient;
    }

    /**
     * @param GuzzleClient $guzzleClient
     */
    public function setGuzzleClient(GuzzleClient $guzzleClient): void
    {
        $this->guzzleClient = $guzzleClient;
    }

}

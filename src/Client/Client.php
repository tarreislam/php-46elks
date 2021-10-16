<?php

namespace Tarre\Php46Elks\Client;

use GuzzleHttp\Client as GuzzleClient;
use Tarre\Php46Elks\Credentials\Resources\Credential;

class Client
{
    protected GuzzleClient $guzzleClient;

    /**
     * @param Credential $credential
     */
    public function __construct(Credential $credential)
    {
        /*
         * Set client
         */
        $this->setGuzzleClient(new GuzzleClient([
            'base_uri' => $credential->getEndpoint(),
            'auth' => [
                'username' => $credential->getUsername(),
                'password' => $credential->getPassword()
            ]
        ]));
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

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method, string $uri, array $options)
    {
        return $this->getGuzzleClient()->request($method, $uri, $options);
    }
}

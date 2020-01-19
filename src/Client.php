<?php

namespace Tarre\Php46Elks;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions as GuzzleHttpRequestOptions;
use Tarre\Php46Elks\Clients\SMS\SMSClient;


class Client
{
    // only for for "SMS" client
    protected $dryRun = false;

    protected $username;
    protected $password;
    protected $baseURL;
    protected $mock = false;
    protected $mockHandler = null;


    /**
     * Client constructor.
     * @param string $username
     * @param string $password
     * @param string $baseURL
     */
    public function __construct(string $username, string $password, string $baseURL = 'https://api.46elks.com/a1/')
    {
        $this->username = $username;
        $this->password = $password;
        $this->baseURL = $baseURL;
    }

    /**
     * Enable when you want to verify your API request without actually sending an SMS to a mobile phone.
     * No SMS message will be sent when this is enabled.
     * @param bool $state
     * @return $this
     */
    public function dryRun($state = true): self
    {
        $this->dryRun = $state;

        return $this;
    }

    /**
     * @return SMSClient
     */
    public function sms(): SMSClient
    {
        return new SMSClient($this->getGuzzleClient(), $this->dryRun);
    }


    /**
     * @param bool $state
     * @return $this
     */
    public function mock($state = true): self
    {
        $this->mock = $state;
        // Create a mock with an empty queue
        $this->mockHandler = new MockHandler([]);

        return $this;
    }


    /**
     * @return MockHandler
     */
    public function mockHandler(): MockHandler
    {
        return $this->mockHandler;
    }


    /**
     * @return bool
     */
    protected function shouldMock()
    {
        return $this->mock;
    }


    /**
     * @return GuzzleHttpClient
     */
    protected function getGuzzleClient(): GuzzleHttpClient
    {
        // static $client;

        // if (!$client) {

        $options = [
            'base_uri' => $this->baseURL,
            GuzzleHttpRequestOptions::AUTH => [
                $this->username, $this->password
            ]
        ];

        // For testing purposes only
        if ($this->shouldMock()) {
            // attach guzzle MockHandler handler to guzzle client
            $options['handler'] = HandlerStack::create($this->mockHandler);
        }

        $client = new GuzzleHttpClient($options);
        //  }

        return $client;
    }

}

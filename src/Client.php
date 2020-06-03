<?php

namespace Tarre\Php46Elks;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions as GuzzleHttpRequestOptions;
use Tarre\Php46Elks\Clients\Account\AccountClient;
use Tarre\Php46Elks\Clients\Number\NumberClient;
use Tarre\Php46Elks\Clients\PhoneCall\PhoneCallClient;
use Tarre\Php46Elks\Clients\SMS\SMSClient;
use Tarre\Php46Elks\Utils\Helper;

class Client
{
    protected $username;
    protected $password;
    protected $baseURL;
    protected $mock = false;
    protected $mockHandler = null;

    /**
     * Client constructor.
     *
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
     * Set the base URL for SMS & MMS resources such as Sms "WhenDelivered" or phone actions "play", "next" etc
     * This option is persistent in the php process. defaultQueryParams could also be set to always append in every request
     *
     * @param $url
     * @param array $defaultQueryParams
     *
     * @return void
     */
    public static function setResourceBaseUrl($url, array $defaultQueryParams = null)
    {
        Helper::setBaseUrl($url, $defaultQueryParams);
    }

    /**
     * @return SMSClient
     */
    public function sms(): SMSClient
    {
        return new SMSClient($this->getGuzzleClient());
    }

    /**
     * @return PhoneCallClient
     */
    public function phone(): PhoneCallClient
    {
        return new PhoneCallClient($this->getGuzzleClient());
    }

    /**
     * @return NumberClient
     */
    public function number(): NumberClient
    {
        return new NumberClient($this->getGuzzleClient());
    }

    /**
     * @return AccountClient
     */
    public function account(): AccountClient
    {
        return new AccountClient($this->getGuzzleClient());
    }

    /**
     * @param bool $state
     *
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

        $options = [
            'base_uri' => $this->baseURL,
            GuzzleHttpRequestOptions::AUTH => [
                $this->username, $this->password
            ],
            /*
             * Guzzle already does this as of guzzle 6
            GuzzleHttpRequestOptions::HEADERS => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
            */
        ];

        // For testing purposes only
        if ($this->shouldMock()) {
            // attach guzzle MockHandler handler to guzzle client
            $options['handler'] = HandlerStack::create($this->mockHandler);
        }

        return new GuzzleHttpClient($options);
    }
}

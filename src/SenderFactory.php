<?php

namespace Tarre\Php46Elks;

use GuzzleHttp\RequestOptions;
use Tarre\Php46Elks\Client\Client;

abstract class SenderFactory
{
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send()
    {
        /**@var QueryBuilderFactory $qbFactory */
        $qbFactories = $this->getQueryBuilderFactories();
        /*
         * Validate and build the factories
         */
        foreach ($qbFactories as $qbFactory) {
            $qbFactory->validate();
            $qbFactory->build();
        }
        /*
         * Make requests
         */
        $res = [];
        foreach ($qbFactories as $qbFactory) {
            $res[] = $this->request($qbFactory->toArray());
        }
        /*
         * Return array of responses
         */
        return $this->mapResult($res);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request(array $params)
    {
        static $requestOption = [
            self::METHOD_GET => RequestOptions::QUERY,
            self::METHOD_POST => RequestOptions::JSON,
        ];

        $headers = $this->headers();
        $uri = $this->uri();
        $method = $this->method();

        $options = [
            RequestOptions::HEADERS => $headers
        ];

        $options = array_merge($options, [
            $requestOption[$method] => $params
        ]);

        $res = $this->guzzle()->get($uri, $options);

        $resAssoc = json_decode($res->getBody()->getContents(), true);

        return $resAssoc;
    }

    protected function guzzle(): \GuzzleHttp\Client
    {
        return $this->client->getGuzzleClient();
    }

    protected function headers(): array
    {
        return [];
    }

    protected abstract function getQueryBuilderFactories(): array;

    protected abstract function uri(): string;

    protected abstract function method(): string;

    protected abstract function mapResult(array $result);

}

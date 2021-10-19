<?php

namespace Tarre\Php46Elks;

use GuzzleHttp\RequestOptions;
use Tarre\Php46Elks\Client\Client;
use Tarre\Php46Elks\Interfaces\QueryBuilder;

abstract class SenderFactory
{
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';

    protected Client $client;
    protected array $requests;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Add requests
     * @param QueryBuilder $request
     * @return $this
     */
    public function addRequest(QueryBuilder $request): SenderFactory
    {
        $this->requests[] = $request;
        return $this;
    }

    /**
     * Remove all requests and set new value
     * @param array $requests
     * @return $this
     */
    public function setRequests(array $requests): self
    {
        $this->requests = [];
        foreach ($requests as $request) {
            $this->addRequest($request);
        }
        return $this;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send()
    {
        /**@var QueryBuilderFactory $qbFactory */
        $qbFactories = $this->requests;
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
         * Map result
         */
        $res = $this->mapResult($res);
        /*
         * Reset requests
         */
        $this->setRequests([]);
        /*
         * Return result
         */
        return $res;
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

    public function headers(): array
    {
        return [];
    }

    protected function guzzle(): \GuzzleHttp\Client
    {
        return $this->client->getGuzzleClient();
    }

    protected abstract function uri(): string;

    protected abstract function method(): string;

    protected abstract function mapResult(array $result);

}

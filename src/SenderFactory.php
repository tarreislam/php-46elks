<?php

namespace Tarre\Php46Elks;

use GuzzleHttp\RequestOptions;
use Tarre\Php46Elks\Client\Client;
use Tarre\Php46Elks\Interfaces\RequestFactoryInterface;

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
     * @param RequestFactoryInterface $request
     * @return $this
     */
    public function addRequest(RequestFactoryInterface $request): SenderFactory
    {
        $this->requests[] = $request;
        return $this;
    }

    /**
     * Remove all requests and set new value
     * @param array $requests
     * @return $this
     */
    public function setRequests(array $requests): SenderFactory
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
    public function send(): array
    {
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
        $res = array_map(fn(RequestFactoryInterface $qbFactory) => $this->request($qbFactory->toArray()), $qbFactories);
        /*
         * Map all requests results
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
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request(array $params): array
    {
        static $requestOption = [
            self::METHOD_GET => RequestOptions::QUERY,
            self::METHOD_POST => RequestOptions::FORM_PARAMS,
        ];

        $options = [
            RequestOptions::HEADERS => $this->headers()
        ];

        $options = array_merge($options, [
            $requestOption[$method = $this->method()] => $params
        ]);

        $res = $this->guzzle()->request($method, $this->uri(), $options);

        $resBodyContents = $res->getBody()->getContents();

        $resAssoc = json_decode($resBodyContents, true);

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

    protected function mapResult(array $result)
    {
        /*
         * Flatten [[1,2], [1,2]] => [1,2,1,2]
         */
        // $reqs = array_merge(...$result);
        /*
         * Get response class
         */
        $class = $this->mapResponse();
        /*
         * Map result
         */
        return array_map(fn($reqs) => new $class($reqs), $result);
    }

    protected abstract function uri(): string;

    protected abstract function method(): string;

    protected abstract function mapResponse(): string;

}

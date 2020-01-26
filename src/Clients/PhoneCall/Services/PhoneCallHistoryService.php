<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Services;


use GuzzleHttp\RequestOptions as GuzzleHttpRequestOptions;
use Tarre\Php46Elks\Clients\PhoneCall\PhoneCallClient;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\CallHistoryResource;
use Tarre\Php46Elks\Interfaces\DataResourceInterface;
use Tarre\Php46Elks\Traits\DataResourceFilterTrait;
use Tarre\Php46Elks\Traits\QueryOptionTrait;
use Tarre\Php46Elks\Utils\Paginator;

/**
 * @property PhoneCallClient phoneCallClient
 */
class PhoneCallHistoryService implements DataResourceInterface
{
    use DataResourceFilterTrait, QueryOptionTrait;

    protected $phoneCallClient;

    public function __construct(PhoneCallClient $client)
    {
        $this->phoneCallClient = $client;
    }

    /**
     * @inheritDoc
     */
    public function get(): Paginator
    {
        //  request with optional options
        $request = $this->phoneCallClient->getGuzzleClient()->get('calls', [
            GuzzleHttpRequestOptions::QUERY => $this->getOptions()
        ]);

        // grab response
        $response = $request->getBody()->getContents();

        // deserialize
        $assoc = json_decode($response, true);

        // create payload
        $payload = [
            'next' => $assoc['next'],
            'data' => array_map(function ($row) {
                return new CallHistoryResource($row);
            }, $assoc['data'])
        ];

        // return our pagination object
        return new Paginator($payload);
    }

    /**
     * @inheritDoc
     * @return CallHistoryResource
     */
    public function getById(string $id): CallHistoryResource
    {
        $request = $this->phoneCallClient->getGuzzleClient()->get(sprintf('calls/%s', $id));

        // grab response
        $response = $request->getBody()->getContents();

        // deserialize
        $assoc = json_decode($response, true);

        // return SMS object
        return new CallHistoryResource($assoc);
    }
}

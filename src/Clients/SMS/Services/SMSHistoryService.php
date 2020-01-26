<?php


namespace Tarre\Php46Elks\Clients\SMS\Services;

use GuzzleHttp\RequestOptions as GuzzleHttpRequestOptions;
use Tarre\Php46Elks\Clients\SMS\Resources\MessageResource;
use Tarre\Php46Elks\Clients\SMS\SMSServiceBase;
use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Traits\QueryOptionTrait;
use Tarre\Php46Elks\Utils\Paginator;
use Tarre\Php46Elks\Utils\Helper;


class SMSHistoryService extends SMSServiceBase
{
    use QueryOptionTrait;

    /**
     * Retrieve SMS before this date.
     * @param $start
     * @return $this
     */
    public function start($start): self
    {
        $this->setOption('start', $start);

        return $this;
    }


    /**
     * Retrieve SMS after this date.
     * @param $end
     * @return $this
     */
    public function end($end): self
    {
        $this->setOption('end', $end);

        return $this;
    }


    /**
     * Limit the number of results on each page.
     * @param $limit
     * @return $this
     */
    public function limit($limit): self
    {
        $this->setOption('limit', $limit);

        return $this;
    }


    /**
     * Filter on recipient.
     * @param $e164PhoneNumber
     * @return $this
     * @throws InvalidE164PhoneNumberFormatException
     */
    public function to($e164PhoneNumber): self
    {
        Helper::validateE164PhoneNumber($e164PhoneNumber);

        $this->setOption('to', $e164PhoneNumber);

        return $this;
    }


    /**
     * @return Paginator
     */
    public function get(): Paginator
    {
        //  request with optional options
        $request = $this->getSMSClient()->getGuzzleClient()->get($this->getMediaType(), [
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
                return new MessageResource($row);
            }, $assoc['data'])
        ];

        // return our pagination object
        return new Paginator($payload);
    }


    /**
     * @param string $id
     * @return MessageResource
     */
    public function getById(string $id): MessageResource
    {
        $request = $this->SMSClient->getGuzzleClient()->get(sprintf('%s/%s', $this->getMediaType(), $id));

        // grab response
        $response = $request->getBody()->getContents();

        // deserialize
        $assoc = json_decode($response, true);

        // return SMS object
        return new MessageResource($assoc);
    }

}

<?php


namespace Tarre\Php46Elks\Clients\SMS\Services;

use GuzzleHttp\RequestOptions as GuzzleHttpRequestOptions;
use Tarre\Php46Elks\Clients\SMS\Resources\Message;
use Tarre\Php46Elks\Clients\SMS\SMSServiceBase;
use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Interfaces\DataResourceInterface;
use Tarre\Php46Elks\Traits\DataResourceFilterTrait;
use Tarre\Php46Elks\Traits\QueryOptionTrait;
use Tarre\Php46Elks\Utils\Paginator;
use Tarre\Php46Elks\Utils\Helper;


class SMSHistoryService extends SMSServiceBase implements DataResourceInterface
{
    use QueryOptionTrait, DataResourceFilterTrait;

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
            'next' => isset($assoc['next']) ? $assoc['next'] : null,
            'data' => array_map(function ($row) {
                return new Message($row);
            }, $assoc['data'])
        ];

        // return our pagination object
        return new Paginator($payload);
    }


    /**
     * @param string $id
     * @return Message
     */
    public function getById(string $id): Message
    {
        $request = $this->SMSClient->getGuzzleClient()->get(sprintf('%s/%s', $this->getMediaType(), $id));

        // grab response
        $response = $request->getBody()->getContents();

        // deserialize
        $assoc = json_decode($response, true);

        // return SMS object
        return new Message($assoc);
    }

}

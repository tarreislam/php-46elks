<?php


namespace Tarre\Php46Elks\Clients\Recording;


use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Clients\Recording\Resources\Recording;
use Tarre\Php46Elks\Traits\DataResourceFilterTrait;
use Tarre\Php46Elks\Traits\QueryOptionTrait;
use Tarre\Php46Elks\Utils\FileResource;
use Tarre\Php46Elks\Utils\Paginator;

class RecordingClient extends BaseClient
{
    use QueryOptionTrait, DataResourceFilterTrait;

    /**
     * Get MMS Recording History
     *
     * @return Paginator
     */
    public function get(): Paginator
    {
        // perform request
        $request = $this->getGuzzleClient()->get('recordings');

        // catch result
        $response = $request->getBody()->getContents();

        // deserialize
        $assoc = json_decode($response, true);

        // create payload
        $payload = [
            'next' => isset($assoc['next']) ? $assoc['next'] : null,
            'data' => array_map(function ($row) {
                return new Recording($row, $this);
            }, $assoc['data'])
        ];

        // return new pagination object
        return new Paginator($payload);
    }

    /**
     * Get MMS Recording History by id
     *
     * @return Paginator
     */
    public function getById(string $id): Recording
    {
        // perform request
        $request = $this->getGuzzleClient()->get("recordings/$id");

        // catch result
        $response = $request->getBody()->getContents();

        // deserialize
        $assoc = json_decode($response, true);

        // return recording object
        return new Recording($assoc, $this);
    }

    /**
     * Returns the recording file of the recording with the specified ID.
     *
     * @param string $id
     * @return FileResource
     */
    public function getFileById(string $id): FileResource
    {

        // perform request
        $res = $this->getGuzzleClient()->get("recordings/$id", $this->getOptions(true));

        // return file resource
        return new FileResource($res->getBody()->getContents(), $this);
    }

}

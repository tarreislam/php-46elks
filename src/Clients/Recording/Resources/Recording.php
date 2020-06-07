<?php


namespace Tarre\Php46Elks\Clients\Recording\Resources;


use Tarre\Php46Elks\Clients\Recording\RecordingClient;
use Tarre\Php46Elks\Interfaces\Arrayable;
use Tarre\Php46Elks\Interfaces\HasFileInterface;
use Tarre\Php46Elks\Utils\FileResource;

/**
 * @property RecordingClient recordingClient
 */
class Recording implements Arrayable, HasFileInterface
{
    protected $data;
    protected $recordingClient;

    public function __construct(array $data, RecordingClient $recordingClient)
    {
        $this->data = $data;
        $this->recordingClient = $recordingClient;
    }


    /**
     * Unique ID for the recording.
     *
     * @return string|null
     */
    public function id()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Time in UTC when the recording was created.
     *
     * @return string|null
     */
    public function created()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Recoring time in seconds
     *
     * @return integer
     */
    public function duration()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : 0;
    }

    /**
     * shortcut for "getFileById" on the resource
     *
     * @return FileResource
     */
    public function withFile(): FileResource
    {
        return $this->recordingClient->getFileById($this->id());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}

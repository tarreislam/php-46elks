<?php


namespace Tarre\Php46Elks\Clients\Image\Resouces;


use Tarre\Php46Elks\Interfaces\Arrayable;

class Image implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * Unique ID for the image.
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
     * File type of the image
     *
     * @return string|null
     */
    public function filetype()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Unique ID for the mms the image was recieved with.
     *
     * @return string|null
     */
    public function mmsid()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Size of the image in bytes.
     *
     * @return string|null
     */
    public function bytes()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Hex digest of the image
     *
     * @return string|null
     */
    public function digest()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}

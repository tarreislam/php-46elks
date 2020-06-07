<?php


namespace Tarre\Php46Elks\Clients\Number\Resources;


use Tarre\Php46Elks\Interfaces\Arrayable;

class Number implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * ID of the allocated numberID of the allocated number
     * @return string|null
     */
    public function id()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Current state of the phone number. "Yes" or "No". If the number is active or not, always "yes" on newly allocated numbers
     * @return string|null
     */
    public function active()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * A two-letter (ISO alpha 2) country code.
     * @return string|null
     */
    public function country()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * The phone number in E.164 format.
     * @return string|null
     */
    public function number()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * A array containing the capabilities of the number ['sms', 'mms', 'voice']
     * @return array
     */
    public function capabilities()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : [];
    }

    /**
     * UTC time when the number was dealloted.
     * @return string|null
     */
    public function allocated()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * UTC time when the number was deallocated.
     * @return string|null
     */
    public function deallocated()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * If the has SMS support this url it where the API will send incomming SMS.
     * @return mixed|null
     */
    public function sms_url()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * If the has Voice support this url it where the API will send incomming calls to be handeled.
     * @return mixed|null
     */
    public function voice_start()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * If the has MMS support this url it where the API will send incomming MMS.
     * @return mixed|null
     */
    public function mms_url()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}

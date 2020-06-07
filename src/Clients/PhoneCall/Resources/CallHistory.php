<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Resources;

use Tarre\Php46Elks\Interfaces\Arrayable;

class CallHistory implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * The result of the call "newincoming", "busy", "failed", "success", "ok"
     *
     * @return mixed|null
     */
    public function result()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * The direction of the call. Set to "outgoing" for calls initated by the API and "incoming" for calls initated by phones.
     *
     * @return string|null
     */
    public function direction()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * The calling part of the call E.164 format.
     *
     * @return string|null
     */
    public function from()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * The phone number that was called in E.164 format.
     *
     * @return string|null
     */
    public function to()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Time in UTC when the call was created.
     *
     * @return string|null
     */
    public function created()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Array of actions taken by the API druing the call, like action connect or action play.
     *
     * @return array
     */
    public function actions(): array
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : [];
    }

    /**
     * Time the call was picked up.
     *
     * @return string|null
     */
    public function start()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Status of the call for example "ongoing" or "success".
     *
     * @return string|null
     */
    public function state()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Cost of the call. Specified in 10000s of the currency of your account. For an account with currency SEK a cost of 1500 means that the price for sending this SMS was 0.15 SEK.
     *
     * @return int
     */
    public function cost(): int
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : 0;
    }

    /**
     * The direction of the call. Set to "outgoing" for calls initated by the API and "incoming" for calls initated by phones.
     *
     * @return int
     */
    public function duration(): int
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : 0;
    }

    /**
     * Array of calls made during the call with call action connect.
     *
     * @return array
     */
    public function legs(): array
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : [];
    }

    /**
     * Unique ID for the call.
     *
     * @return string|null
     */
    public function id()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->data;
    }
}

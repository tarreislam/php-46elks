<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Resources;


use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallRouterService;
use Tarre\Php46Elks\Interfaces\Arrayable;

class ReceivedPhoneCall implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function direction()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }


    /**
     * The unique ID of the call in our systems.
     * @return string|null
     */
    public function callId()
    {
        return isset($this->data['callid']) ? $this->data['callid'] : null;
    }


    /**
     * The sender of the call.
     * @return string|null
     */
    public function from()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }


    /**
     * The phone number receiving the call.
     * @return string|null
     */
    public function to()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }


    /**
     * The time in UTC when the call object was created in our systems.
     * @return string|null
     */
    public function created()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * Return a new router instance
     * @return PhoneCallRouterService
     */
    public function router(): PhoneCallRouterService
    {
        return new PhoneCallRouterService;
    }

    /**
     * Return a new action
     * @return PhoneCallAction
     */
    public function action(): PhoneCallAction
    {
        return new PhoneCallAction;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}

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
     * @return mixed
     */
    public function direction()
    {
        return $this->data['direction'];
    }


    /**
     * The unique ID of the call in our systems.
     * @return mixed
     */
    public function callId()
    {
        return $this->data['callid'];
    }


    /**
     * The sender of the call.
     * @return mixed
     */
    public function from()
    {
        return $this->data['from'];
    }


    /**
     * The phone number receiving the call.
     * @return mixed
     */
    public function to()
    {
        return $this->data['to'];

    }


    /**
     * The time in UTC when the call object was created in our systems.
     * @return mixed
     */
    public function created()
    {
        return $this->data['created'];

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

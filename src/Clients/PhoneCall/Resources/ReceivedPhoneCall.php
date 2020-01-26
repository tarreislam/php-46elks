<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Resources;


use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallActionRouter;

class ReceivedPhoneCall
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
     * @return PhoneCallActionRouter
     */
    public function router(): PhoneCallActionRouter
    {
        return new PhoneCallActionRouter;
    }
}

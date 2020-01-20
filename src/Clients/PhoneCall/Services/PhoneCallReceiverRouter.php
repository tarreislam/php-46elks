<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Services;


use Tarre\Php46Elks\Clients\PhoneCall\Exceptions\InvalidActionException;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;

class PhoneCallReceiverRouter
{
    protected $callbacks;

    /**
     * @param $name
     * @param callable $fn
     * @return $this
     */
    public function register($name, callable $fn): self
    {
        $this->callbacks[$name] = $fn;
    }

    /**
     * @param null $baseUrl
     * @param null $action
     * @return void
     * @throws InvalidActionException
     */
    public function handle($baseUrl, $action)
    {
        $this->callbacks[$action](new PhoneCallAction($baseUrl));
    }

}

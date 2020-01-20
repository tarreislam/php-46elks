<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Services;


use Exception;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;
use Tarre\Php46Elks\Exceptions\InvalidActionException;

class PhoneCallReceiverRouter
{
    protected $callbacks;
    protected $compiledCallbacks = [];
    protected $baseUrl;
    protected $compiled;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }


    /**
     * @param $name
     * @param callable $fn
     * @return $this
     */
    public function register($name, callable $fn): self
    {
        $this->callbacks[$name] = $fn;

        return $this;
    }


    /**
     * Compile all previously registered routes
     * @return $this
     */
    public function compile(): self
    {
        $this->compiled = true;

        foreach ($this->callbacks as $name => $callback) {
            $this->compiledCallbacks[$name] = $callback(new PhoneCallAction($this->baseUrl));
        }

        return $this;
    }


    /**
     * @param $action
     * @return void
     * @throws InvalidActionException
     * @throws Exception
     */
    public function handle($action)
    {
        $this->throwIfNotCompiled();

        return $this->callbacks[$action](new PhoneCallAction($this->baseUrl));
    }


    /**
     * @return false|string
     * @throws Exception
     */
    public function __toString()
    {
        $this->throwIfNotCompiled();
        // serialize all routes

        $serializedCallbacks = array_map(function ($r) {
            return (string)$r;
        }, $this->compiledCallbacks);

        // return serialized router
        return json_encode($serializedCallbacks);
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function throwIfNotCompiled()
    {
        if (!$this->compiled) {
            throw new Exception('You must compile all registered routes first.');
        }
    }

}

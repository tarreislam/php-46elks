<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Services;


use Exception;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;
use Tarre\Php46Elks\Exceptions\InvalidActionException;

class PhoneCallActionRouter
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
     * Compile all previously registered routes or provide an array to "install" from an array
     * @param array|null $array
     * @return $this
     */
    public function compile(array $array = null): self
    {

        if (is_null($array)) {
            foreach ($this->callbacks as $name => $callback) {

                $callable = $callback(new PhoneCallAction($this->baseUrl));

                // only compile if callback is valid
                if ($callable instanceof PhoneCallAction) {
                    $this->compiledCallbacks[$name] = $callable->toArray();

                }

            }
        } else {
            foreach ($array as $name => $subArray) {
                $this->compiledCallbacks[$name] = $subArray;
            }
        }

        $this->compiled = true;

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
        if ($this->compiled) {
            return $this->compiledCallbacks[$action]();
        } else {
            return $this->callbacks[$action](new PhoneCallAction($this->baseUrl));
        }

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
            return $r;
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

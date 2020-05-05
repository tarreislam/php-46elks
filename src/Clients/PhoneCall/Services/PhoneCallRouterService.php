<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Services;


use Exception;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;
use Tarre\Php46Elks\Exceptions\InvalidActionException;
use Tarre\Php46Elks\Exceptions\RouteActionNotFoundException;
use Tarre\Php46Elks\Exceptions\RouteNameReservedException;

class PhoneCallRouterService
{
    const DEFAULT_ROUTE_NAME = 'default';

    protected $callbacks;
    protected $compiledCallbacks = [];
    protected $compiled;

    /**
     * @param $name
     * @param callable $fn
     * @return $this
     * @throws RouteNameReservedException
     */
    public function register($name, callable $fn): self
    {
        if ($name === self::DEFAULT_ROUTE_NAME) {
            throw new RouteNameReservedException(sprintf('The route name "%s" is reserved. use the default() method instead', self::DEFAULT_ROUTE_NAME));
        }
        $this->callbacks[$name] = $fn;

        return $this;
    }

    /**
     * @param callable $fn
     * @return $this
     */
    public function default(callable $fn): self
    {
        $this->callbacks[self::DEFAULT_ROUTE_NAME] = $fn;

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

                $callable = $callback(new PhoneCallAction);

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
     * Handle an previously registered action. Non compiled callbacks will be compiled here
     * @param $action
     * @return mixed
     * @throws InvalidActionException
     * @throws Exception
     */
    public function handle($action)
    {
        //
        if ($this->compiled && isset($this->compiledCallbacks[$action])) {
            $result = $this->compiledCallbacks[$action];
        } else if (isset($this->callbacks[$action])) {
            // compile action and return it as an array
            $result = $this->callbacks[$action](new PhoneCallAction)->toArray();
        } else {
            // If the registered route was not found. Try to fallback on the default route
            if ($this->compiled && isset($this->compiledCallbacks[self::DEFAULT_ROUTE_NAME])) {
                $result = $this->compiledCallbacks[self::DEFAULT_ROUTE_NAME];
            } elseif (isset($this->callbacks[self::DEFAULT_ROUTE_NAME])) {
                $result = $this->callbacks[self::DEFAULT_ROUTE_NAME](new PhoneCallAction)->toArray();
            } else {
                // no mamez MIERDA
                throw new RouteActionNotFoundException(sprintf('No suitable route found'));
            }
        }

        // determine if we need to invoke anything
        if (isset($result['_invoke']) && is_array($result['_invoke'])) {
            $invoke = $result['_invoke'];
            // (new $invoke['class'])->$invoke['method']();
            call_user_func([$invoke['class'], $invoke['method']]);
        }

        // get rid of internal keys
        $result = array_filter($result, function ($key) {
            return !preg_match('/^_/', $key);
        }, ARRAY_FILTER_USE_KEY);


        // Return compiled action
        return $result;
    }


    /**
     * @return false|string
     * @throws Exception
     */
    public function __toString()
    {
        // serialize all routes
        return $this->toJson();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function toJson(): string
    {
        $this->throwIfNotCompiled();

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

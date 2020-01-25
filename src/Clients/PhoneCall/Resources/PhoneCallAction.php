<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Resources;

use InvalidArgumentException;
use Tarre\Php46Elks\Exceptions\ActionIsAlreadySetException;
use Tarre\Php46Elks\Exceptions\InvalidActionException;
use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Traits\QueryOptionTrait;
use Tarre\Php46Elks\Utils\Validator;

class PhoneCallAction
{
    use QueryOptionTrait;

    protected $baseUrl;
    protected $actionAlreadySet;
    protected $denyNextAction;


    /**
     * PhoneCallAction constructor.
     * @param null $baseUrl
     */
    public function __construct($baseUrl = null)
    {
        // fix baseUrl if its present
        if (!is_null($baseUrl)) {
            // trim
            $baseUrl = trim($baseUrl);
            // trim ending slashes
            $baseUrl = preg_replace('/\/+$/', '', $baseUrl);
        }
        $this->baseUrl = $baseUrl;
        $this->actionAlreadySet = false;
        $this->denyNextAction = false;
    }

    public function toArray()
    {
        return $this->getOptions();
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->getOptions());

    }

    /**
     * Invoke a method class, the returned data wont be used
     * @param $method
     * @param null $class
     * @return $this
     * @throws InvalidActionException
     */
    public function invoke($class, $method = null)
    {
        $callable = null;

        if ($this->hasOption('_invoke')) {
            throw new InvalidActionException('invoke already set');
        }

        // determine structure
        if (is_object($class) && is_string($method)) {
            $callable = [
                'class' => get_class($class),
                'method' => $method
            ];
        } else if (strpos($class, '@') && is_null($method)) {
            $parts = explode('@', $class);
            $callable = [
                'class' => $parts[0],
                'method' => $parts[1]
            ];
        } else if (is_string($class) && is_string($method)) {
            $callable = [
                'class' => $class,
                'method' => $method
            ];
        }

        // see if any combination was ok
        if (is_null($callable)) {
            throw new InvalidActionException('Invalid combination');
        }

        // see if the given class and or method exists
        if (!method_exists($callable['class'], $callable['method'])) {
            throw new InvalidActionException(sprintf('Class or method does not exist: %s@%s', $callable['class'], $callable['method']));
        }


        return $this->setOption('_invoke', $callable);
    }


    /**
     * @param $uri
     * @param array|null $options
     * @return $this
     * @throws ActionIsAlreadySetException
     */
    public function next($uri, array $options = null): self
    {
        $this->throwIfNextActionIsDenied();

        // prepare url
        $url = $this->fullUrl($uri, $options);

        return $this->setOption('next', $url);
    }


    /**
     * Connect the call to a given number, and in the case of an answer, let the two callers speak to each other.
     * The callerid indicates who is calling. You can set it to one of your 46elks numbers.
     * If you leave it out, it will default to the incoming caller’s number.
     * @param string|array $e164PhoneNumber
     * @param null $callerId
     * @return $this
     * @throws InvalidE164PhoneNumberFormatException
     * @throws ActionIsAlreadySetException
     */
    public function connect($e164PhoneNumber, $callerId = null): self
    {
        $this->throwIfActionIsAlreadyDecided();

        if (!is_array($e164PhoneNumber)) {
            $e164PhoneNumbers = explode(',', $e164PhoneNumber);
        } else {
            $e164PhoneNumbers = $e164PhoneNumber;
        }

        foreach ($e164PhoneNumbers as $number) {
            Validator::validateE164PhoneNumber($number);
        }

        if (!is_null($callerId)) {
            Validator::validateE164PhoneNumber($callerId);
            $this->setOption('callerid', $callerId);
        }

        $e164PhoneNumber = implode(',', $e164PhoneNumbers);

        return $this->decideAction('connect', $e164PhoneNumber);
    }

    /**
     * Play an audio file or predefined sound. Could be either a URL on your server or a sound resource provided by 46elks.
     * URLs are fetched using HTTP GET and are always cached, so for unique resources be sure to provide unique URLs.
     * If a digit is pressed during “play” or if the audio playback has been completed, the call continues at “next”.
     * You can force the full audio file to be played by setting the option “skippable” to false.
     * @param $url
     * @param bool $skippable
     * @return $this
     * @throws ActionIsAlreadySetException
     */
    public function play($url, bool $skippable = null): self
    {
        $this->throwIfActionIsAlreadyDecided();

        if (!is_null($skippable)) {
            $this->setOption('skippable', $skippable);
        }

        $url = $this->fullUrl($url);

        return $this->decideAction('play', $url);
    }

    /**
     * The “ivr” action fills the purpose of playing a sound resource while also retrieving digits pressed by the caller (think customer support menus etc.).
     *  * Repeat: You can choose how many times the voice response, your sound resource, is repeated using the “repeat” key
     *  * Time out: You can choose how many seconds to wait for input with the “timeout” key.
     * @param $urlToPlay
     * @param int $digits
     * @param int $timeout
     * @param int $repeat
     * @return $this
     * @throws ActionIsAlreadySetException
     */
    public function ivr($urlToPlay, $digits = 1, $timeout = 30, $repeat = 3): self
    {
        $this->throwIfActionIsAlreadyDecided();

        if (!is_numeric($digits)) {
            throw new InvalidArgumentException('digits has to be numeric');
        } else {
            $this->setOption('digits', $digits);
        }

        if (!is_numeric($timeout)) {
            throw new InvalidArgumentException('timeout has to be numeric');
        } else {
            $this->setOption('timeout', $timeout);
        }

        if (!is_numeric($repeat)) {
            throw new InvalidArgumentException('repeat to be numeric');
        } else {
            $this->setOption('repeat', $repeat);
        }

        $urlToPlay = $this->fullUrl($urlToPlay);

        return $this->decideAction('ivr', $urlToPlay);
    }


    /**
     * @param $url
     * @return $this
     * @throws ActionIsAlreadySetException
     */
    public function record($url): self
    {
        $this->throwIfActionIsAlreadyDecided();

        $url = $this->fullUrl($url);

        $this->setOption('record', $url);

        return $this->decideAction('ivr', $url);
    }


    /**
     * This action records the entire call and sends out a webhook with a link to the recording when the call ends.
     * This action cannot be used by itself, it triggers at the same time as another action.
     * @param $url
     * @return $this
     */
    public function recordCall($url): self
    {
        $url = $this->fullUrl($url);

        $this->setOption('recordcall', $url);

        return $this;
    }

    /**
     * End the call. If this is your first action, it is possible to control signalling, otherwise only "reject" is allowed.
     * @param string $state
     * @return $this
     */
    public function hangUp($state = 'busy')
    {
        $this->denyNextAction = true;

        if (!preg_match('/^(?:busy|reject|404)$/', $state)) {
            throw new InvalidArgumentException(sprintf('invalid state "%s"', $state));
        }

        return $this->decideAction('hangup', $state);
    }

    /**
     * @throws ActionIsAlreadySetException
     */
    protected function throwIfActionIsAlreadyDecided()
    {
        if ($this->actionAlreadySet) {
            throw new ActionIsAlreadySetException;
        }
    }

    /**
     * @throws ActionIsAlreadySetException
     */
    protected function throwIfNextActionIsDenied()
    {
        if ($this->denyNextAction) {
            throw new ActionIsAlreadySetException;
        }
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    protected function decideAction($key, $value): self
    {
        $this->actionAlreadySet = true;
        $this->setOption($key, $value);
        return $this;
    }

    /**
     * @param $uri
     * @param array|null $options
     * @return string
     */
    protected function fullUrl($uri, array $options = null)
    {
        // The url is not relative.
        if (preg_match('/^(?:ftp|http|\/\/|\\\\)/', $uri)) {
            return $uri;
        }

        // prepend baseUrl if its present
        if (!is_null($this->baseUrl)) {
            $url = sprintf('%s/%s', $this->baseUrl, $uri);
        } else {
            $url = $uri;
        }

        // append query params
        if (!is_null($options)) {
            $url .= http_build_query($options);
        }

        return $url;
    }
}

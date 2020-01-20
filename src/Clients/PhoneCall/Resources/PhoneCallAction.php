<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Resources;

use InvalidArgumentException;
use Tarre\Php46Elks\Clients\PhoneCall\Exceptions\ActionIsAlreadySetException;
use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Traits\QueryOptionTrait;

class PhoneCallAction
{
    use QueryOptionTrait;

    protected $baseUrl;
    protected $actionAlreadySet;
    protected $denyNextAction;

    /**
     * PhoneCallAction constructor.
     * @param $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->actionAlreadySet = false;
        $this->denyNextAction = false;
    }


    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->getOptions());
    }


    /**
     * @param $uri
     * @return void
     * @throws ActionIsAlreadySetException
     */
    public function next($uri): self
    {
        $this->throwIfNextActionIsDenied();

        if (!preg_match('/^(?:http|\/\/|\\\\)/', $uri)) {
            $uri = sprintf('%s/%s', $this->baseUrl, $uri);
        }

        $this->setOption('next', $uri);
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
            if (!preg_match('/^\+\d{1,3}\d+/', $number)) {
                throw new InvalidE164PhoneNumberFormatException($number);
            }
        }

        if (!is_null($callerId) && !preg_match('/^\+\d{1,3}\d+/', $callerId)) {
            throw new InvalidE164PhoneNumberFormatException($callerId);
        }

        if (!is_null($callerId)) {
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
        }

        if (!is_numeric($timeout)) {
            throw new InvalidArgumentException('timeout has to be numeric');
        }

        if (!is_numeric($repeat)) {
            throw new InvalidArgumentException('repeat to be numeric');
        }

        return $this->decideAction('ivr', $urlToPlay);
    }


    /**
     * This action records the entire call and sends out a webhook with a link to the recording when the call ends.
     * This action cannot be used by itself, it triggers at the same time as another action.
     * @return $this
     */
    public function recordCall($uri): self
    {
        $this->setOption('recordcall', $uri);

        return $this;
    }

    /**
     * End the call. If this is your first action, it is possible to control signalling, otherwise only "reject" is allowed.
     * @param string $state
     * @return void
     */
    public function hangUp($state = 'busy')
    {
        $this->denyNextAction = true;

        if (!preg_match('/^(?:busy|reject|404)$/', $state)) {
            throw new InvalidArgumentException(sprintf('invalid state "%s"', $state));
        }
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
}

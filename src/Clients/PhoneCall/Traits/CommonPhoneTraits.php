<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Traits;


use Tarre\Php46Elks\Clients\SMS\Traits\CommonSmsTraits;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;
use Tarre\Php46Elks\Utils\Helper;

trait CommonPhoneTraits
{
    protected $senderId = null;

    /**
     * @param string $senderId
     * @return $this
     * @throws InvalidSenderIdException
     */
    public function from(string $senderId): self
    {
        Helper::validateSenderID($senderId);

        $this->senderId = $senderId;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getFrom()
    {
        return $this->senderId;
    }


    /**
     * @param $url
     * @return $this
     */
    public function whenHangup($url): self
    {
        return $this->setOption('whenhangup', $url);
    }


    /**
     * @param $url
     * @return mixed
     */
    public function voiceStart($url)
    {
        return $this->setOption('voice_start', $url);
    }


    /**
     * @param int $timeout
     * @return mixed
     */
    public function timeout($timeout = 60)
    {
        return $this->setOption('timeout', $timeout);
    }
}

<?php


namespace Tarre\Php46Elks\Clients\SMS\Traits;

use Tarre\Php46Elks\Clients\SMS\Services\SMSDispatcherService;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;
use Tarre\Php46Elks\Utils\Helper;

trait CommonSmsTraits
{
    protected $senderId = null;

    /**
     * @param string $senderId
     * @return $this
     * @throws InvalidSenderIdException
     */
    public function from(string $senderId): self
    {
        if (!preg_match('/[a-z]+[a-z0-9]+/i', $senderId)) {
            throw new InvalidSenderIdException($senderId);
        }

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
     * Send the message as a Flash SMS. The message will be displayed immediately upon arrival and not stored.
     * @return $this
     */
    public function flashSms(): self
    {
        return $this->setOption('flashsms', 'yes');
    }


    /**
     * Enable when you want to verify your API request without actually sending an SMS to a mobile phone.
     * No SMS message will be sent when this is enabled.
     * @return $this
     */
    public function dryRun(): self
    {
        return $this->setOption('dryrun', 'yes');
    }


    /**
     * This webhook URL will receive a POST request every time the delivery status changes. The url can be relative if a base URL
     * @return $this
     */
    public function whenDelivered($url): self
    {
        $url = Helper::url($url);

        return $this->setOption('whendelivered', $url);
    }
}

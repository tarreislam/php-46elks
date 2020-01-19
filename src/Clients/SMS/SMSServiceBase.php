<?php


namespace Tarre\Php46Elks\Clients\SMS;

use Tarre\Php46Elks\Exceptions\InvalidSMSClientTypeException;

/**
 * @property SMSClient SMSClient
 * @property string $mediaType
 */
abstract class SMSServiceBase
{
    protected $mediaType;
    protected $SMSClient;

    /**
     * SMSServiceBase constructor.
     * @param SMSClient $SMSClient
     * @param string $mediaType
     * @throws InvalidSMSClientTypeException
     */
    public function __construct(SMSClient $SMSClient, $mediaType = 'sms')
    {
        if (!preg_match('/^(?:sms|mms)$/', $mediaType)) {
            throw new InvalidSMSClientTypeException;
        }

        $this->SMSClient = $SMSClient;
        $this->mediaType = $mediaType;
    }

    /**
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * @return SMSClient
     */
    public function getSMSClient()
    {
        return $this->SMSClient;
    }

}

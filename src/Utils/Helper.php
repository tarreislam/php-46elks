<?php


namespace Tarre\Php46Elks\Utils;

use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;

class Helper
{

    const E164PhoneNumberRE = '\+\d{1,3}\d+';

    protected static $baseUrl;

    /**
     * @return mixed
     */
    public static function getBaseUrl()
    {
        return self::$baseUrl;
    }

    /**
     * @param $baseUrl
     */
    public static function setBaseUrl($baseUrl)
    {
        // trim
        $baseUrl = trim($baseUrl);
        // trim ending slashes
        self::$baseUrl = preg_replace('/\/+$/', '', $baseUrl);
    }

    /**
     * Provide the full url of a given uri
     * @param $uri
     * @param array|null $options
     * @param null $baseUrl
     * @return string
     */
    public static function url($uri, array $options = null, $baseUrl = null)
    {

        // trim ending slashes
        $uri = preg_replace('/\/+$/', '', $uri);

        // grab baseUrl
        if (is_null($baseUrl)) {
            $baseUrl = self::getBaseUrl();
        }

        // The url is not relative.
        if (preg_match('/^(?:ftp|http|\/\/|\\\\)/', $uri)) {
            return $uri;
        }

        // prepend baseUrl if its present
        if (!is_null($baseUrl)) {
            $url = sprintf('%s/%s', $baseUrl, $uri);
        } else {
            $url = $uri;
        }

        // append query params
        if (!is_null($options)) {
            $url .= http_build_query($options);
        }

        return $url;
    }

    /**
     * @param $phoneNumber
     * @return void
     * @throws InvalidE164PhoneNumberFormatException
     */
    public static function validateE164PhoneNumber($phoneNumber)
    {
        if (!preg_match('/^' . self::E164PhoneNumberRE . '$/', $phoneNumber)) {
            throw new InvalidE164PhoneNumberFormatException($phoneNumber);
        }
    }

    /**
     * @param $senderId
     * @throws InvalidSenderIdException
     */
    public static function validateSenderID($senderId)
    {
        if (!preg_match('/^(?:[a-z]{1}[a-z0-9]{2,10}|' . self::E164PhoneNumberRE . ')$/i', $senderId)) {
            throw new InvalidSenderIdException($senderId);
        }
    }

}

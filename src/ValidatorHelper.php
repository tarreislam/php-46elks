<?php

namespace Tarre\Php46Elks;

class ValidatorHelper
{
    // https://46elks.se/kb/text-sender-id
    const RE_TEXT_SENDER_ID = '/^[a-z]{1}[a-z-A-Z0-9]{0,10}$/';
    // https://46elks.se/kb/e164
    const RE_E_164_PHONE_NUMBER = '/^\+\d{2,}/';

    public static bool $overrideValidator = false;

    /**
     * @param string $senderId
     * @return bool
     */
    public static function isValidSenderId(string $senderId)
    {
        if (self::$overrideValidator) {
            return true;
        }
        return !!preg_match(self::RE_TEXT_SENDER_ID, $senderId);
    }

    /**
     * @param string $number
     * @return bool
     */
    public static function isValidE164PhoneNubmer(string $number)
    {
        if (self::$overrideValidator) {
            return true;
        }
        return !!preg_match(self::RE_E_164_PHONE_NUMBER, $number);
    }

    /**
     * @param string $mixed
     * @return bool
     */
    public static function isValidSenderOrE164(string $mixed)
    {
        return self::isValidSenderId($mixed) || self::isValidE164PhoneNubmer($mixed);
    }

    /**
     * @param string $number
     */
    public static function isValidMultiPartE164PhoneNumber(string $number)
    {
        $number = preg_replace('/\h+/', '', $number);
        $parts = explode(',', $number);
        foreach ($parts as $part) {
            if (!self::isValidE164PhoneNubmer($part)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $url
     * @return mixed
     */
    public static function isValidUrl(string $url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * @param string $url
     * @return false|void
     */
    public static function isValidMultipartUrl(string $url)
    {
        $parts = explode(',', $url);
        foreach ($parts as $part) {
            if (!self::isValidUrl($part)) {
                return false;
            }
        }
        return true;
    }

}

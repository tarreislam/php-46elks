<?php


namespace Tarre\Php46Elks\Clients\SMS;

use Tarre\Php46Elks\Clients\PhoneCall\PhoneCallClient;

/**
 * @property PhoneCallClient $phoneCallClient
 */
abstract class PhoneCallServiceBase
{
    protected $phoneCallClient;

    /**
     * SMSServiceBase constructor.
     * @param PhoneCallClient $PhoneCallClient
     */
    public function __construct(PhoneCallClient $PhoneCallClient)
    {
        $this->phoneCallClient = $PhoneCallClient;
    }

    /**
     * @return PhoneCallClient
     */
    public function getPhoneCallClient()
    {
        return $this->phoneCallClient;
    }

}

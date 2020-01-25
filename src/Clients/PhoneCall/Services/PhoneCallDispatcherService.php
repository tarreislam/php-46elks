<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Services;


use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Clients\SMS\Traits\SenderTrait;
use Tarre\Php46Elks\Traits\QueryOptionTrait;

class PhoneCallDispatcherService
{
    use QueryOptionTrait, SenderTrait;

    public function __construct(BaseClient $client)
    {
        // TODO inget är klart här
        // TODO inget är klart här
        // TODO inget är klart här
        // TODO inget är klart här
    }

    public function voiceStart($url)
    {
        return $this->setOption('voice_start', $url);
    }

    public function whenHangup($url)
    {
        return $this->setOption('whenhangup', $url);
    }

    public function timeout($timeout = 60)
    {
        return $this->setOption('timeout', $timeout);
    }


}

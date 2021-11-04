<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait ToTrait
{
    protected string $to;

    /**
     * The phone number the SMS was sent to.
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

}

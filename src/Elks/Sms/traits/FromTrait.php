<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait FromTrait
{
    protected string $from;

    /**
     * The sender of the SMS as seen by the recipient.
     * String may start with a letter and contain numbers - Max 11 characters including A-Z, a-z, 0-9.
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }
}

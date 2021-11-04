<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait MessageTrait
{
    protected string $message;

    /**
     * The message text.
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}

<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait StatusTrait
{
    protected string $status;

    /**
     * Current delivery status of the message.
     * Possible values are "created", "sent", "failed" and "delivered".
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

}

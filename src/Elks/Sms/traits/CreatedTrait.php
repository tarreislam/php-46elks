<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait CreatedTrait
{
    protected string $created;

    /**
     * The time in UTC when the SMS object was created in our systems.
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

}

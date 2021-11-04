<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait DontLogTrait
{
    protected string $dont_log;

    /**
     * @return string
     * Set to "message" if dontlog was enabled.
     */
    public function getDontLog(): string
    {
        return $this->dont_log;
    }
}

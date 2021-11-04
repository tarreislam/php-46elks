<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait PartsTrait
{
    protected int $parts;

    /**
     * Number of parts the SMS was divided into.
     * @return int
     */
    public function getParts(): int
    {
        return $this->parts;
    }
}

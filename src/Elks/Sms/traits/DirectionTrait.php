<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait DirectionTrait
{
    protected string $direction;

    /**
     * The direction of the SMS. Set to "outgoing" for sent SMS.
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }
}

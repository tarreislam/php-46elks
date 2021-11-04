<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait CostTrait
{
    protected ?int $cost = 0;

    /**
     * Cost of sending the SMS. Specified in 10000s of the currency of your account. For an account with currency SEK a cost of 3500 means that the price for sending this SMS was 0.35 SEK.
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }


}

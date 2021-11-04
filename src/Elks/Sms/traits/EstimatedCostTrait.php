<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait EstimatedCostTrait
{
    protected int $estimated_cost;

    /**
     * Replaces cost in the response if dryrun was enabled.
     * @return int
     */
    public function getEstimatedCost(): int
    {
        return $this->estimated_cost;
    }

}

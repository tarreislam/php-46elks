<?php

namespace Tarre\Php46Elks\Traits;

trait DataResourceFilterTrait
{
    /**
     * Retrieve resources before this date.
     * @param $start
     * @return $this
     */
    public function start($start): self
    {
        $this->setOption('start', $start);

        return $this;
    }


    /**
     * Retrieve resources after this date.
     * @param $end
     * @return $this
     */
    public function end($end): self
    {
        $this->setOption('end', $end);

        return $this;
    }


    /**
     * Limit the number of results on each page.
     * @param $limit
     * @return $this
     */
    public function limit($limit): self
    {
        $this->setOption('limit', $limit);

        return $this;
    }
}

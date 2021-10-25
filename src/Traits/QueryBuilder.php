<?php

namespace Tarre\Php46Elks\Traits;

use Tarre\Php46Elks\Exceptions\ResultNotFoundException;

trait QueryBuilder
{
    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get()
    {
        return $this->send();
    }

    /**
     * @return mixed|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function first()
    {
        $res = $this->send();
        if (count($res) > 0) {
            return $res[0];
        }
        return null;
    }

    /**
     * @throws ResultNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function firstOrFail()
    {
        $res = $this->first();
        if (is_null($res)) {
            throw new ResultNotFoundException($this->uri());
        }
    }
}

<?php

namespace Tarre\Php46Elks;

abstract class ConstructSetter
{
    public function __construct(array $rows)
    {
        foreach ($rows as $key => $val) {
            $this->{$key} = $val;
        }
    }
}

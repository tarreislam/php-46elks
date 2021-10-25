<?php

namespace Tarre\Php46Elks;

abstract class ConstructSetterFactory
{
    public function __construct(array $rows)
    {
        foreach ($rows as $key => $val) {
            $this->{$key} = $val;
        }
    }
}

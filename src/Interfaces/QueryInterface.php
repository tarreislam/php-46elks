<?php

namespace Tarre\Php46Elks\Interfaces;

interface QueryInterface
{
    public function whereId($id);

    public function first();

    public function firstOrFail();

    public function get();
}

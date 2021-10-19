<?php

namespace Tarre\Php46Elks\Interfaces;

interface RequestFactoryInterface
{
    public function set(string $key, $val);

    public function get(string $key);

    public function toArray(): array;

    public function toJson(): string;

    public function validate(): void;

    public function build(): void;
}

<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait IdTrait
{
    protected ?string $id = '';

    /**
     * Unique identifier for this SMS.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}

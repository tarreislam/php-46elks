<?php

namespace Tarre\Php46Elks\Elks\Sms\traits;

trait ImagesTrait
{
    protected array $images;

    /**
     * Array of strings of all images
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

}

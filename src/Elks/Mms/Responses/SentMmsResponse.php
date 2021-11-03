<?php

namespace Tarre\Php46Elks\Elks\Mms\Responses;

use Tarre\Php46Elks\Elks\Sms\Responses\SentSmsResponse;

class SentMmsResponse extends SentSmsResponse
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

<?php

namespace Tarre\Php46Elks\Elks\Mms\Responses;

use Tarre\Php46Elks\ConstructSetterFactory;
use Tarre\Php46Elks\Elks\Exceptions\InvalidNumberToForwardToException;
use Tarre\Php46Elks\Elks\Sms\Responses\ReceivedSmsResponse;
use Tarre\Php46Elks\ValidatorHelper;

class ReceivedMmsResponse extends ReceivedSmsResponse
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

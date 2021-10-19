<?php

namespace Tarre\Php46Elks\Elks\Mms\Requests;

use Tarre\Php46Elks\Elks\Mms\Exceptions\InvalidMmsImageValueException;
use Tarre\Php46Elks\Elks\Sms\Requests\SmsMessageRequest;
use Tarre\Php46Elks\ValidatorHelper;

class MmsMessageRequest extends SmsMessageRequest
{
    protected string $image;

    public function validate(): void
    {
        if (!ValidatorHelper::isValidMultipartUrl($this->getImage())) {
            throw new InvalidMmsImageValueException($this->getImage());
        }
        parent::validate();
    }

    public function build(): void
    {
        $this->set('image', $this->getImage());
        parent::build();
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

}

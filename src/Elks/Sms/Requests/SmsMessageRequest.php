<?php

namespace Tarre\Php46Elks\Elks\Sms\Requests;

use Tarre\Php46Elks\Elks\Exceptions\InvalidMultipartSenderE164NumberException;
use Tarre\Php46Elks\Elks\Exceptions\InvalidSenderIdOrE164NumberException;
use Tarre\Php46Elks\Elks\Exceptions\InvalidUrlException;
use Tarre\Php46Elks\Elks\Sms\Exceptions\InvalidDryRunValueException;
use Tarre\Php46Elks\Elks\Sms\Exceptions\InvalidFlashValueException;
use Tarre\Php46Elks\ValidatorHelper;
use Tarre\Php46Elks\QueryBuilderFactory;

class SmsMessageRequest extends QueryBuilderFactory
{
    // Required
    protected string $from;
    protected string $to;
    protected string $message;
    // optional
    protected string $dryRun;
    protected string $whenDelivered;
    protected string $flash;
    protected string $dontLog;

    public function validate(): void
    {
        if (!ValidatorHelper::isValidE164PhoneNubmer($this->getFrom())) {
            throw new InvalidSenderIdOrE164NumberException($this->getFrom());
        }

        if (!ValidatorHelper::isValidMultiPartE164PhoneNumber($this->getTo())) {
            throw new InvalidMultipartSenderE164NumberException($this->getTo());
        }

        if (!is_null($this->getDryRun()) && !in_array($this->getDryRun(), ['yes', 'no'])) {
            throw new InvalidDryRunValueException($this->getDryRun());
        }

        if (!is_null($this->getFlash()) && !in_array($this->getFlash(), ['yes', 'no'])) {
            throw new InvalidFlashValueException($this->getFlash());
        }

        if (!is_null($this->getWhenDelivered()) && !ValidatorHelper::isValidUrl($this->getWhenDelivered())) {
            throw new InvalidUrlException($this->getWhenDelivered());
        }

    }

    public function build(): void
    {
        // required
        $this->set('from', $this->getFrom());
        $this->set('to', $this->getTo());
        $this->set('message', $this->getMessage());
        // optional
        if (!is_null($this->getDryRun())) {
            $this->set('dry_run', $this->getDryRun());
        }
        if (!is_null($this->getWhenDelivered())) {
            $this->set('whendelivered', $this->getWhenDelivered());
        }
        if (!is_null($this->getFlash())) {
            $this->set('flash', $this->getFlash());
        }
        if (!is_null($this->getDontLog())) {
            $this->set('dontlog', $this->getDontLog());
        }
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return SmsMessageRequest
     */
    public function setFrom(string $from): SmsMessageRequest
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return SmsMessageRequest
     */
    public function setTo(string $to): SmsMessageRequest
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return SmsMessageRequest
     */
    public function setMessage(string $message): SmsMessageRequest
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getDryRun(): string
    {
        return $this->dryRun;
    }

    /**
     * @param string $dryRun
     * @return SmsMessageRequest
     */
    public function setDryRun(string $dryRun): SmsMessageRequest
    {
        $this->dryRun = $dryRun;
        return $this;
    }

    /**
     * @return string
     */
    public function getWhenDelivered(): string
    {
        return $this->whenDelivered;
    }

    /**
     * @param string $whenDelivered
     * @return SmsMessageRequest
     */
    public function setWhenDelivered(string $whenDelivered): SmsMessageRequest
    {
        $this->whenDelivered = $whenDelivered;
        return $this;
    }

    /**
     * @return string
     */
    public function getFlash(): string
    {
        return $this->flash;
    }

    /**
     * @param string $flash
     * @return SmsMessageRequest
     */
    public function setFlash(string $flash): SmsMessageRequest
    {
        $this->flash = $flash;
        return $this;
    }

    /**
     * @return string
     */
    public function getDontLog(): string
    {
        return $this->dontLog;
    }

    /**
     * @param string $dontLog
     * @return SmsMessageRequest
     */
    public function setDontLog(string $dontLog): SmsMessageRequest
    {
        $this->dontLog = $dontLog;
        return $this;
    }

}

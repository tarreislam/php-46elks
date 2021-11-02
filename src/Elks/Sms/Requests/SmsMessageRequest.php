<?php

namespace Tarre\Php46Elks\Elks\Sms\Requests;

use Tarre\Php46Elks\Elks\Exceptions\InvalidMultipartSenderE164NumberException;
use Tarre\Php46Elks\Elks\Exceptions\InvalidSenderIdOrE164NumberException;
use Tarre\Php46Elks\Exceptions\InvalidUrlException;
use Tarre\Php46Elks\Elks\Sms\Exceptions\InvalidDryRunValueException;
use Tarre\Php46Elks\Elks\Sms\Exceptions\InvalidFlashValueException;
use Tarre\Php46Elks\ValidatorHelper;
use Tarre\Php46Elks\RequestFactory;

class SmsMessageRequest extends RequestFactory
{
    // Required
    protected ?string $from = '';
    protected ?string $to = '';
    protected ?string $message = '';
    // optional
    protected ?string $dryRun = '';
    protected ?string $whenDelivered = '';
    protected ?string $flash = '';
    protected ?string $dontLog = '';

    public function validate(): void
    {
        if (!ValidatorHelper::isValidSenderOrE164($from = $this->getFrom())) {
            throw new InvalidSenderIdOrE164NumberException($from);
        }

        if (!ValidatorHelper::isValidMultiPartE164PhoneNumber($to = $this->getTo())) {
            throw new InvalidMultipartSenderE164NumberException($to);
        }

        if (!empty($dryRun = $this->getDryRun()) && !in_array($dryRun, ['yes', 'no'])) {
            throw new InvalidDryRunValueException($dryRun);
        }

        if (!empty($flash = $this->getFlash()) && !in_array($flash, ['yes', 'no'])) {
            throw new InvalidFlashValueException($flash);
        }

        if (!empty($whenDelivered = $this->getWhenDelivered()) && !ValidatorHelper::isValidUrl($whenDelivered)) {
            throw new InvalidUrlException($whenDelivered);
        }

    }

    public function build(): void
    {
        // required
        $this->set('from', $this->getFrom());
        $this->set('to', $this->getTo());
        $this->set('message', $this->getMessage());
        // optional
        if (!empty($this->getDryRun())) {
            $this->set('dryrun', $this->getDryRun());
        }
        if (!empty($this->getWhenDelivered())) {
            $this->set('whendelivered', $this->getWhenDelivered());
        }
        if (!empty($this->getFlash())) {
            $this->set('flash', $this->getFlash());
        }
        if (!empty($this->getDontLog())) {
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
     * The sender of the SMS as seen by the recipient. Either a text sender ID or a phone number in E.164 format if you want to be able to receive replies.
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
     * The phone number of the recipient in E.164 format.
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
     * The message to send.
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
    public function getDryRun(): ?string
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
    public function getWhenDelivered(): ?string
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
    public function getFlash(): ?string
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
    public function getDontLog(): ?string
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

<?php

namespace Tarre\Php46Elks\Elks\Sms\Responses;

class SmsMessage
{
    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';
    const STATUS_DELIVERED = 'delivered';

    protected string $status;
    protected string $id;
    protected string $from;
    protected string $to;
    protected string $message;
    protected string $created;
    protected string $delivered;
    protected int $cost;
    protected string $direction;
    protected int $estimated_cost;
    protected int $parts;

    public function __construct(array $rows)
    {
        foreach ($rows as $key => $val) {
            $this->{$key} = $val;
        }

    }

    /**
     * Current delivery status of the message.
     *
     * Possible values are "created", "sent", "failed" and "delivered".
     * Possible CONST's are SmsMessage::STATUS_CREATED, SmsMessage::STATUS_SENT, SmsMessage::STATUS_FAILED, SmsMessage::STATUS_DELVIERED,
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Unique identifier for this SMS.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * The sender of the SMS as seen by the recipient.
     * String may start with a letter and contain numbers - Max 11 characters including A-Z, a-z, 0-9.
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getDelivered(): string
    {
        return $this->delivered;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return int
     */
    public function getEstimatedCost(): int
    {
        return $this->estimated_cost;
    }

    /**
     * @return int
     */
    public function getParts(): int
    {
        return $this->parts;
    }


}

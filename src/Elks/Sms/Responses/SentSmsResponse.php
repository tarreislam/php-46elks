<?php

namespace Tarre\Php46Elks\Elks\Sms\Responses;

use Tarre\Php46Elks\ConstructSetter;

class SentSmsResponse extends ConstructSetter
{
    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';
    const STATUS_DELIVERED = 'delivered';

    const DIRECTION_OUTGOING = 'outgoing';

    protected string $status;
    protected string $id;
    protected string $from;
    protected string $to;
    protected string $message;
    protected string $created;
    protected string $delivered;
    protected int $cost;
    protected string $direction;
    protected string $dont_log;
    protected int $estimated_cost;
    protected int $parts;

    /**
     * Current delivery status of the message.
     * Possible values are "created", "sent", "failed" and "delivered".
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
     * The phone number of the recipient in E.164 format.
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     *    The message text.
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     *    Time in UTC when the SMS was created.
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * Time in UTC if the SMS has been successfully delivered.
     * @return string
     */
    public function getDelivered(): string
    {
        return $this->delivered;
    }

    /**
     *    Cost of sending the SMS. Specified in 10000s of the currency of your account. For an account with currency SEK a cost of 3500 means that the price for sending this SMS was 0.35 SEK.
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * The direction of the SMS. Set to "outgoing" for sent SMS.
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return string
     * Set to "message" if dontlog was enabled.
     */
    public function getDontLog(): string
    {
        return $this->dont_log;
    }

    /**
     * Replaces cost in the response if dryrun was enabled.
     * @return int
     */
    public function getEstimatedCost(): int
    {
        return $this->estimated_cost;
    }

    /**
     * Number of parts the SMS was divided into.
     * @return int
     */
    public function getParts(): int
    {
        return $this->parts;
    }
}

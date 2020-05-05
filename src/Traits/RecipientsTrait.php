<?php


namespace Tarre\Php46Elks\Traits;

use phpDocumentor\Reflection\Types\This;
use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Utils\Helper;

trait RecipientsTrait
{
    protected $recipients = [];

    /**
     * Add a recipient
     * @param string $e164PhoneNumber
     * @return $this
     * @throws InvalidE164PhoneNumberFormatException
     */
    public function recipient(string $e164PhoneNumber): self
    {
        Helper::validateE164PhoneNumber($e164PhoneNumber);

        // add only if not added
        if (!in_array($e164PhoneNumber, $this->recipients)) {
            $this->recipients[] = $e164PhoneNumber;
        }

        return $this;
    }

    /**
     * @param array $recipients
     * @return $this
     * @throws InvalidE164PhoneNumberFormatException
     */
    public function setRecipients(array $recipients): self
    {
        foreach ($recipients as $recipient) {
            $this->recipient($recipient);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * Remove added recipients
     * @return self
     */
    public function removeAllRecipients()
    {
        $this->recipients = [];
        return $this;
    }

}

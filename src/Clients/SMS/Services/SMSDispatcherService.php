<?php


namespace Tarre\Php46Elks\Clients\SMS\Services;

use GuzzleHttp\RequestOptions as GuzzleHttpRequestOptions;
use Tarre\Php46Elks\Clients\SMS\SMSServiceBase;
use Tarre\Php46Elks\Clients\SMS\Traits\CommonSmsTraits;
use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;
use Tarre\Php46Elks\Exceptions\NoRecipientsSetException;
use Tarre\Php46Elks\Interfaces\RequestStructureInterface;
use Tarre\Php46Elks\Traits\QueryOptionTrait;
use Tarre\Php46Elks\Utils\Helper;


class SMSDispatcherService extends SMSServiceBase implements RequestStructureInterface
{
    use QueryOptionTrait, CommonSmsTraits;

    protected $lines = [];
    protected $recipients = [];

    /**
     * Add another recipients
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
     * @param string $line
     * @param bool $trim
     * @return $this
     */
    public function line(string $line = '', $trim = true): self
    {
        // We can trim to keep the char count lower of our message
        if ($trim) {
            // strip HTML and PHP tags
            $line = strip_tags($line);
            // convert newlines to only \n
            $line = preg_replace("/\r\n|\r/", "\n", $line);
            // replace tabs with spaces
            $line = preg_replace("/\t+/", ' ', $line);
            // trim
            $line = trim($line);
        }

        $this->lines[] = $line;

        return $this;
    }


    /**
     * Set the lines of the message
     * @param array $lines
     * @param bool $trim
     * @return $this
     */
    public function setLines(array $lines, $trim = true): self
    {
        foreach ($lines as $line) {
            $this->line($line, $trim);
        }

        return $this;
    }


    /**
     * Set the content
     * @param string $body
     * @param bool $trim
     * @return $this
     */
    public function content(string $body, $trim = true): self
    {
        $body = preg_split("/\r\n|\r|\n/", $body);

        $this->setLines($body, $trim);

        return $this;
    }


    /**
     * Get the final message content
     * @return string
     */
    public function getMessage(): string
    {
        return implode("\n", $this->lines);
    }


    /**
     * @param $image
     * @return $this
     */
    public function image($image): self
    {
        $this->setOption('image', $image);

        return $this;
    }


    /**
     * @return array
     * @throws InvalidSenderIdException
     * @throws NoRecipientsSetException
     */
    public function getRequests(): array
    {
        // determine which senderID we want to use.
        $from = $this->getFrom() ?: $this->SMSClient->getFrom();
        $message = $this->getMessage();
        $childOptions = $this->getOptions();
        $parentOptions = $this->SMSClient->getOptions();
        $recipients = $this->getRecipients();

        if (is_null($from)) {
            throw new InvalidSenderIdException;
        }

        if (count($recipients) == 0) {
            throw new NoRecipientsSetException;
        }

        // Union options into a single array
        $payload = [
                'from' => $from,
                'message' => $message
            ] +
            $childOptions +
            $parentOptions;

        // create request collection with all recipients
        return array_map(function ($recipient) use ($payload) {
            return $requestCollection[] = [
                    'to' => $recipient,
                ] +
                $payload;
        }, $recipients);
    }


    /**
     * @return array
     * @throws InvalidSenderIdException
     * @throws NoRecipientsSetException
     */
    public function send(): array
    {
        // dispatch requests and return a collection of results
        return array_map(function ($request) {

            $postRequest = $this->getSMSClient()->getGuzzleClient()->post($this->getMediaType(), [
                GuzzleHttpRequestOptions::JSON => $request
            ]);

            return json_decode($postRequest->getBody()->getContents(), true);
        }, $this->getRequests());
    }

}

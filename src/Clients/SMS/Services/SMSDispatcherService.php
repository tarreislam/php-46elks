<?php


namespace Tarre\Php46Elks\Clients\SMS\Services;

use GuzzleHttp\RequestOptions as GuzzleHttpRequestOptions;
use Tarre\Php46Elks\Clients\SMS\SMSServiceBase;
use Tarre\Php46Elks\Clients\SMS\Traits\CommonSmsTraits;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;
use Tarre\Php46Elks\Exceptions\NoRecipientsSetException;
use Tarre\Php46Elks\Interfaces\RequestStructureInterface;
use Tarre\Php46Elks\Traits\QueryOptionTrait;
use Tarre\Php46Elks\Traits\RecipientsTrait;

class SMSDispatcherService extends SMSServiceBase implements RequestStructureInterface
{
    use QueryOptionTrait, CommonSmsTraits, RecipientsTrait;

    protected $lines = [];

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
     * Set the lines of the message. Previously set lines or content() will be removed.
     * @param array $lines
     * @param bool $trim
     * @return $this
     */
    public function setLines(array $lines, $trim = true): self
    {
        // reset lines
        $this->removeAllLines();

        // add new lines
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
     * @return self
     */
    public function removeAllLines()
    {
        $this->lines = [];
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
            return [
                    'to' => $recipient,
                ] +
                $payload;
        }, $recipients);
    }


    /**
     * Send request. This will reset the message content (lines) and recipients, but preserve "from" and custom options
     * @return array
     * @throws InvalidSenderIdException
     * @throws NoRecipientsSetException
     */
    public function send(): array
    {
        // dispatch requests and return a collection of results
        $result = array_map(function ($request) {

            $postRequest = $this->getSMSClient()->getGuzzleClient()->post($this->getMediaType(), [
                GuzzleHttpRequestOptions::FORM_PARAMS => $request
            ]);

            return json_decode($postRequest->getBody()->getContents(), true);
        }, $this->getRequests());

        // reset message data and recipients, but preserve the rest
        $this->removeAllLines();
        $this->removeAllRecipients();

        return $result;
    }

}

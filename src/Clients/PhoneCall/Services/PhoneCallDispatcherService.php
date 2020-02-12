<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Services;

use GuzzleHttp\RequestOptions as GuzzleHttpRequestOptions;
use InvalidArgumentException;
use Tarre\Php46Elks\Clients\PhoneCall\PhoneCallClient;
use Tarre\Php46Elks\Clients\PhoneCall\Traits\CommonPhoneTraits;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;
use Tarre\Php46Elks\Exceptions\NoRecipientsSetException;
use Tarre\Php46Elks\Interfaces\RequestStructureInterface;
use Tarre\Php46Elks\Traits\QueryOptionTrait;
use Tarre\Php46Elks\Traits\RecipientsTrait;

/**
 * @property PhoneCallClient phoneCallClient
 */
class PhoneCallDispatcherService implements RequestStructureInterface
{
    use QueryOptionTrait, CommonPhoneTraits, RecipientsTrait;

    protected $phoneCallClient;

    public function __construct(PhoneCallClient $client)
    {
        $this->phoneCallClient = $client;
    }

    /**
     * @inheritDoc
     * @throws InvalidSenderIdException
     * @throws NoRecipientsSetException
     */
    public function getRequests(): array
    {
        // get options
        $childOptions = $this->getOptions();
        $parentOptions = $this->phoneCallClient->getOptions();
        $recipients = $this->getRecipients();

        // Unionize
        $payload =
            $childOptions +
            $parentOptions;

        // Validate
        if (!isset($payload['from'])) {
            throw new InvalidSenderIdException;
        }

        if (count($recipients) == 0) {
            throw new NoRecipientsSetException;
        }

        if (!isset($payload['voice_start'])) {
            throw new InvalidArgumentException(sprintf('Missing vital argument voice_start'));
        }

        // create request collection with all recipients
        return array_map(function ($recipient) use ($payload) {
            return [
                    'to' => $recipient,
                ] +
                $payload;
        }, $recipients);
    }

    /**
     * Send request
     * @return array
     * @throws InvalidSenderIdException
     * @throws NoRecipientsSetException
     */
    public function send(): array
    {
        // dispatch requests and return a collection of results
        return array_map(function ($request) {

            $postRequest = $this->phoneCallClient->getGuzzleClient()->post('calls', [
                GuzzleHttpRequestOptions::FORM_PARAMS => $request
            ]);

            return json_decode($postRequest->getBody()->getContents(), true);
        }, $this->getRequests());
    }
}

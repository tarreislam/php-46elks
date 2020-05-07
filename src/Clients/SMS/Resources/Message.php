<?php


namespace Tarre\Php46Elks\Clients\SMS\Resources;


use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Interfaces\Arrayable;
use Tarre\Php46Elks\Utils\Helper;


class Message implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * The unique id of the message in our systems.
     * @return mixed
     */
    public function id()
    {
        return $this->data['id'];
    }


    /**
     * The sender of the SMS.
     * @return mixed
     */
    public function from()
    {
        return $this->data['from'];
    }


    /**
     * The phone number the SMS was sent to.
     * @return mixed
     */
    public function to()
    {
        return $this->data['to'];
    }


    /**
     * The contents of the SMS.
     * @return mixed
     */
    public function message()
    {
        return $this->data['message'];
    }


    /**
     * The direction of the SMS. "incoming" or "outgoing"
     * @return mixed
     */
    public function direction()
    {
        return $this->data['direction'];
    }


    /**
     * The time in UTC when the SMS object was created in our systems.
     * @return mixed
     */
    public function created()
    {
        return $this->data['created'];
    }


    /**
     * The time in UTC when the SMS was delivered.
     * @return mixed
     */
    public function delivered()
    {
        return $this->data['delivered'] ?: null;
    }


    /**
     * created (recieved by our servers), sent (sent from us to the carrier), delivered (confirmed delivered to the recipient) or failed (could not be delivered)
     * @return mixed
     */
    public function status()
    {
        return $this->data['status'] ?: null;
    }


    /**
     * The cost of sending the SMS. Specified in 10000s of the currency of the account (SEK or EUR). For example, for an account with currency SEK, a cost of 3500 means that it cost 0.35SEK. Learn more about the details of pricing
     * @return mixed
     */
    public function cost()
    {
        return $this->data['cost'] ?: 0;
    }

    /**
     * @return array
     */
    public function images(): array
    {
        return $this->data['images'] ?: [];
    }


    /**
     * The “reply” action sends an automatic reply back when receiving an SMS.
     * To reply to an received SMS, you should print this string with a 200-204 status (IE echo)
     * @param $text
     * @return false|string
     */
    public function reply($text)
    {
        return json_encode(['reply' => $text]);
    }


    /**
     * The “forward” action forwards incoming SMS to the defined phone number.
     * prefix and suffix variables can optionally be used to add text to the beginning or the end of the message.
     * To forward an received SMS, you should print this string with a 200-204 status (IE echo)
     * @param $e164PhoneNumber
     * @param null|string $prefix
     * @param null|string $suffix
     * @return false|string
     * @throws InvalidE164PhoneNumberFormatException
     */
    public function forward($e164PhoneNumber, $prefix = null, $suffix = null)
    {
        Helper::validateE164PhoneNumber($e164PhoneNumber);

        $payload = ['forward' => $e164PhoneNumber];

        if ($prefix) {
            $payload['prefix'] = $prefix;
        }

        if ($suffix) {
            $payload['suffix'] = $suffix;
        }

        return json_encode($payload);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}

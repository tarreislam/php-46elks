<?php


namespace Tarre\Php46Elks\Clients\Number;


use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Clients\Number\Resources\Number;
use Tarre\Php46Elks\Exceptions\InvalidNumberCapabilityException;
use Tarre\Php46Elks\Exceptions\InvalidNumberCategoryException;
use Tarre\Php46Elks\Exceptions\InvalidNumberOptionException;
use Tarre\Php46Elks\Traits\QueryOptionTrait;

class NumberClient extends BaseClient
{
    use QueryOptionTrait;


    /**
     * @param string $country A two-letter (ISO alpha 2) country code.
     * @param array|string $capabilities A comma-seperated string or an array denoting the capabilities the number should have. For example "sms", "sms,voice" or "sms,mms,voice".
     * @param string $category fixed or mobile
     * @param array $options key-value array containing the "optional" parameters from https://46elks.se/docs/allocate-number
     * @return Number
     * @throws InvalidNumberCategoryException
     * @throws InvalidNumberOptionException
     * @throws InvalidNumberCapabilityException
     */
    public function allocate(string $country, $capabilities, string $category, array $options): Number
    {
        if (!is_array($capabilities)) {
            $capabilities = explode(',', $capabilities);
        }

        // validate capabilities
        foreach ($capabilities as $capability) {
            if (!in_array($capability, ['sms', 'mms', 'voice'])) {
                throw new InvalidNumberCapabilityException(sprintf('Invalid number capability "%s"', $capability));
            }
        }

        // validate category
        if (!in_array($category, ['fixed', 'mobile'])) {
            throw new InvalidNumberCategoryException(sprintf('Invalid category, expected "fixed" or "mobile" got "%s"', $category));
        }

        // validate combination
        if ($category == 'fixed' && in_array('sms', $capabilities)) {
            throw new InvalidNumberCapabilityException(sprintf('You cannot have a fixed number with SMS capabilities'));
        }

        // validate options
        $this->validateNumberOptions($options);

        // prepare capabilities
        $capabilities = implode(',', $capabilities);

        // setup request
        $this->setOption('country', $country);
        $this->setOption('capabilities', $capabilities);
        $this->setOption('category', $category);
        $this->setOptions($options);

        // perform request
        $response = $this->getGuzzleClient()->post('numbers', $this->getOptions(true));

        // catch result
        $json = $response->getBody()->getContents();
        $array = json_decode($json, true);

        // return number resource
        return new Number($array);
    }

    /**
     * @param string $id
     * @param string $active Must be ”no” to deallocate the number.
     * @return Number
     */
    public function deallocate(string $id, $active = 'yes'): Number
    {
        // setup request
        $this->setOption('active', $active);

        // perform request
        $response = $this->getGuzzleClient()->post("numbers/$id", $this->getOptions(true));

        // catch result
        $json = $response->getBody()->getContents();
        $array = json_decode($json, true);

        // return number resource
        return new Number($array);
    }

    public function configure(string $id, array $options)
    {
        $this->validateNumberOptions($options);

        // setup request
        $this->setOptions($options);

        // perform request
        $response = $this->getGuzzleClient()->post("numbers/$id", $this->getOptions(true));

        // catch result
        $json = $response->getBody()->getContents();
        $array = json_decode($json, true);

        // return number resource
        return new Number($array);
    }

    /**
     * @param array $options
     * @throws InvalidNumberOptionException
     */
    protected function validateNumberOptions(array $options)
    {
        // validate key

        foreach ($options as $key => $value) {
            if (!in_array($key, ['sms_url', 'voice_start', 'mms_url', 'sms_replies'])) {
                throw new InvalidNumberOptionException(sprintf('Only "sms_url", "voice_start" and "mms_url" is allowed'));
            }

            if ($key == 'sms_replies') {
                if (!in_array($value, ['yes', 'no'])) {
                    throw new InvalidNumberOptionException(sprintf('The value of "%s" must be "yes" or "no" "%s" given', $key, $value));
                }
            } else {
                if (json_decode($value) === null || !filter_var($key, FILTER_VALIDATE_URL)) {
                    throw new InvalidNumberOptionException(sprintf('The value of "%s" must be json or an valid url. "%s" given', $key, $value));
                }
            }
        }

    }
}

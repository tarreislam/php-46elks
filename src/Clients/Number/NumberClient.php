<?php


namespace Tarre\Php46Elks\Clients\Number;


use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Clients\Number\Resources\Number;
use Tarre\Php46Elks\Exceptions\InvalidNumberCapabilityException;
use Tarre\Php46Elks\Exceptions\InvalidNumberCategoryException;
use Tarre\Php46Elks\Exceptions\InvalidNumberOptionException;
use Tarre\Php46Elks\Traits\QueryOptionTrait;
use Tarre\Php46Elks\Utils\Paginator;

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
    public function allocate(string $country, $capabilities, string $category, array $options = []): Number
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
        $country = strtolower($country);

        // setup request
        $this->setOption('country', $country);
        $this->setOption('capabilities', $capabilities);
        $this->setOption('category', $category);
        $this->setOptions($options);

        // perform request
        $request = $this->getGuzzleClient()->post('numbers', $this->getOptions(true));

        // catch result
        $response = $request->getBody()->getContents();
        $assoc = json_decode($response, true);

        // return number resource
        return new Number($assoc);
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
        $request = $this->getGuzzleClient()->post("numbers/$id", $this->getOptions(true));

        // catch result
        $response = $request->getBody()->getContents();
        $assoc = json_decode($response, true);

        // return number resource
        return new Number($assoc);
    }

    /**
     * @param string $id
     * @param array $options
     * @return Number
     * @throws InvalidNumberOptionException
     */
    public function configure(string $id, array $options): Number
    {
        // validate options
        $this->validateNumberOptions($options);

        // setup request
        $this->setOptions($options);

        // perform request
        $request = $this->getGuzzleClient()->post("numbers/$id", $this->getOptions(true));

        // catch result
        $response = $request->getBody()->getContents();
        $assoc = json_decode($response, true);

        // return number resource
        return new Number($assoc);
    }

    /**
     * @param string $id
     * @return Number
     */
    public function getById(string $id): Number
    {
        // perform request
        $request = $this->getGuzzleClient()->post("numbers/$id", $this->getOptions(true));

        // catch result
        $response = $request->getBody()->getContents();
        $assoc = json_decode($response, true);

        // return number resource
        return new Number($assoc);
    }

    /**
     * List all virtual phone numbers
     * @return Paginator
     */
    public function get(): Paginator
    {
        // perform request
        $request = $this->getGuzzleClient()->post('numbers', $this->getOptions(true));

        // catch result
        $response = $request->getBody()->getContents();

        // deserialize
        $assoc = json_decode($response, true);

        // create payload
        $payload = [
            'next' => isset($assoc['next']) ? $assoc['next'] : null,
            'data' => array_map(function ($row) {
                return new Number($row);
            }, $assoc['data'])
        ];

        // return new pagination object
        return new Paginator($payload);
    }

    /**
     * @param array $options
     * @throws InvalidNumberOptionException
     *
     * @return void
     */
    protected function validateNumberOptions(array $options): void
    {
        $validKeys = ['sms_url', 'voice_start', 'mms_url', 'sms_replies'];

        // validate option array
        foreach ($options as $key => $value) {
            if (!in_array($key, $validKeys)) {
                throw new InvalidNumberOptionException(sprintf('Only "%s" is allowed "%s" given', implode(', ', $validKeys), $key));
            }

            if ($key == 'sms_replies' && !in_array($value, ['yes', 'no'])) {
                throw new InvalidNumberOptionException(sprintf('The value of "%s" must be "yes" or "no" "%s" given', $key, $value));
            } else if (json_decode($value) == null && !filter_var($value, FILTER_VALIDATE_URL)) {
                throw new InvalidNumberOptionException(sprintf('The value of "%s" must be json or an valid url. "%s" given', $key, $value));
            }
        }

    }
}

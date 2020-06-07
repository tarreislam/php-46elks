<?php


namespace Tarre\Php46Elks\Clients\Account\Resources;


use Tarre\Php46Elks\Interfaces\Arrayable;

class Account implements Arrayable
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Unique account id.
     *
     * @return string|null
     *
     */
    public function id()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * The currency used by the account, for example SEK or EUR.
     *
     * @return string|null
     *
     */
    public function currency()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * The name of the account.
     *
     * @return string|null
     *
     */
    public function displayname()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * The cell phone number of the account owner in E.164 format.
     *
     * @return string|null
     *
     */
    public function mobilenumber()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * The email of the account owner.
     *
     * @return string|null
     *
     */
    public function email()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : null;
    }

    /**
     * 	Current balance of the account.
     *
     * @return integer
     *
     */
    public function balance()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : 0;
    }

    /**
     * 	The level when 46elks will send a reminder to the account owner about low balance.
     *
     * @return integer
     *
     */
    public function creditalert()
    {
        return isset($this->data[__FUNCTION__]) ? $this->data[__FUNCTION__] : 0;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}

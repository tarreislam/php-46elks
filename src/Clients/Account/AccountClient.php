<?php


namespace Tarre\Php46Elks\Clients\Account;


use Tarre\Php46Elks\Clients\Account\Resources\Account;
use Tarre\Php46Elks\Clients\BaseClient;
use Tarre\Php46Elks\Traits\QueryOptionTrait;

class AccountClient extends BaseClient
{
    use QueryOptionTrait;

    /**
     * Get current account information (alias for ->get())
     *
     * @return Account
     */
    public function me(): Account
    {
        return $this->get();
    }

    /**
     * Get current account information
     *
     * @return Account
     */
    public function get(): Account
    {
        // perform request
        $request = $this->getGuzzleClient()->get('me');

        // catch result
        $response = $request->getBody()->getContents();
        $assoc = json_decode($response, true);

        // return account  resource
        return new Account($assoc);
    }


    /**
     * Update account information
     *
     * @param int $creditalert
     * @return Account
     */
    public function update(int $creditalert): Account
    {
        // setup params
        $this->setOption('creditalert', $creditalert);

        // perform request
        $request = $this->getGuzzleClient()->post('post', $this->getOptions(true));

        // catch result
        $response = $request->getBody()->getContents();
        $assoc = json_decode($response, true);

        // return account  resource
        return new Account($assoc);
    }
}

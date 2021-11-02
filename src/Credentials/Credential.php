<?php

namespace Tarre\Php46Elks\Credentials;

use Tarre\Php46Elks\Exceptions\InvalidUrlException;
use Tarre\Php46Elks\ValidatorHelper;

class Credential
{
    protected string $username;
    protected string $password;
    protected string $endpoint;

    public function __construct(string $username, string $password, string $endpoint = null)
    {
        $this->setUsername($username);
        $this->setPassword($password);
        /*
         * Default endpoint for 46elks
         */
        if (!$endpoint) {
            $this->setEndpoint('https://api.46elks.com/a1/');
        }
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Credential
     */
    public function setUsername(string $username): Credential
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Credential
     */
    public function setPassword(string $password): Credential
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $endpoint
     * @return Credential
     * @throws InvalidUrlException
     */
    public function setEndpoint(string $endpoint): Credential
    {
        if (!ValidatorHelper::isValidUrl($endpoint)) {
            throw new InvalidUrlException($endpoint);
        }
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Defaults to 'https://api.46elks.com/a1'
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

}

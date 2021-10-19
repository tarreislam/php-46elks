<?php

namespace Tarre\Php46Elks\Credentials;

class Credential
{
    protected string $username;
    protected string $password;
    protected string $endpoint;

    public function __construct()
    {
        $this->endpoint = 'https://api.46elks.com/a1';
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
     */
    public function setEndpoint(string $endpoint): Credential
    {
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

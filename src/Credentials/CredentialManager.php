<?php

namespace Tarre\Php46Elks\Credentials;

use Tarre\Php46Elks\Credentials\Resources\Credential;

class CredentialManager
{
    protected static array $credentials = [];

    public function addCredential(string $username, string $password, string $endPoint = null): void
    {
        /*
         * Set default endpoint if not provided
         */
        if (is_null($endPoint)) {
            $endPoint = 'https://api.46elks.com/a1';
        }
        /*
         * Create new credential
         */
        $credential = (new Credential)
            ->setUsername($username)
            ->setPassword($password)
            ->setEndpoint($endPoint);
        /*
         * Store credential
         */
        self::$credentials[] = $credential;
    }

    /**
     * @return array[]
     */
    public function getAllCredentials(): array
    {
        return self::$credentials;
    }

}

<p align="center"><img src="https://i.imgur.com/HW22gy9.png"></p>

<p align="center">
<a href="https://circleci.com/gh/tarreislam/php-46elks/tree/master"><img src="https://img.shields.io/circleci/build/github/tarreislam/php-46elks?style=flat-square"></a>
<a href="https://packagist.org/packages/tarre/php-46elks"><img src="https://img.shields.io/packagist/php-v/tarre/php-46elks?style=flat-square"></a>
<a href="https://packagist.org/packages/tarre/php-46elks"><img src="https://img.shields.io/packagist/v/tarre/php-46elks?style=flat-square"></a>
<a href="https://packagist.org/packages/tarre/php-46elks"><img src="https://img.shields.io/packagist/l/tarre/php-46elks?style=flat-square"></a>
</p>

## About PHP-46Elks

PHP-46Elks is a wrapper for [46elks.se](46elks.se), written to streamline the interaction between 46elks API and your
PHP application.

[Click here for 1.0 branch (php 7.1 and up)](https://github.com/tarreislam/php-46elks/tree/1.x)

For Laravel applications, please check out [Laravel-46elks](https://github.com/tarreislam/laravel-46elks) instead

### Installation

install with composer

```
composer require tarre/php-46elks
```

### Example of sending SMS

```php
use Tarre\Php46Elks\Client\Client;
use Tarre\Php46Elks\Credentials\Credential;
use Tarre\Php46Elks\Elks\Sms\Requests\SmsMessageRequest;
use Tarre\Php46Elks\Elks\Sms\Responses\SentSmsResponse;
use Tarre\Php46Elks\Elks\Sms\SmsDispatcher;
/*
 * Create credential
 */
$credential = new Credential('username', 'password');
/*
 * Create client and attach credential
 */
$client = new Client($credential);
/*
 * Create SmsDispatcher and attach the client
 */
$smsDispatcher = new SmsDispatcher($client);
/*
 * Create one or more SMS requests
 */
$smsMessage = (new SmsMessageRequest)
    ->setFrom('tarre')
    ->setMessage('Hello')
    ->setTo('+46701474417');
/*
 * Add one or more requests
 */
$smsDispatcher->addRequest($smsMessage);
/*
 * Send
 */
$res = $smsDispatcher->send();
/*
 * Displat result
 */
 (function(SentSmsResponse $response){
 var_dump([
 $response->getId(),
 $response->getParts()
])
 })($res[0])
```

### Deep dive

* [Credentials and clients](docs/creds-n-clients.md)
* [SMS](docs/sms.md)


* [Phone calls](docs/call.md)
* [Numbers](docs/number.md)
* [Account](docs/account.md)
* [Images](docs/image.md)
* [Recordings](docs/recording.md)


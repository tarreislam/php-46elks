# Credentials and clients

```php
use Tarre\Php46Elks\Client\Client;
use Tarre\Php46Elks\Credentials\Credential;
/*
 * The credential store  holds your username, password and custom url for 46elks (if needed)
 */
$credential = new Credential('username', 'password', 'https://custom_url');
/*
 * Different clients can utilize the same credential
 */
$client1 = new Client($credential);
$client2 = new Client($credential);
/*
 * Its also possible to set your own guzzle client to the client
 */
$client2->setGuzzleClient(new GuzzleHttp\Client([]));
```

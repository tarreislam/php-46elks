# The number Client

The Phone client wraps the number section of [46elks.se docs](https://46elks.se/docs/allocate-number)

## Available services

Unlike [Sms](sms.md) and [Phone calls](call.md) no sub services exist for the number client.

* [Accessing the number client](#accessing-the-number-client)

### <a id="accessing-the-number-client"></a> Access the client

The number client may be accessed like this

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$number = $Php46ElksClient->number();

```
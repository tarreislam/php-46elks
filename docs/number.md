# The number Client

The Phone client wraps the number section of [46elks.se docs](https://46elks.se/docs/allocate-number)

## Available services

Unlike [Sms](sms.md) and [Phone calls](call.md) no sub services exist for the number client.

* [Accessing the number client](#accessing-the-number-client)
* [Allocate number](#allocate-number)
* [Deallocate number](#deallocate-number)
* [Configure number](#configure-number)
* [Get all](#get-all)
* [Get by id](#get-by-id)


### <a id="accessing-the-number-client"></a> Access the client

The number client may be accessed like this

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$number = $Php46ElksClient->number();
```

### <a id="allocate-number"></a> Allocate a number

Allocate a virtual phone number

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$number = $Php46ElksClient->number();

$result = $number->allocate('+46701474415', ['sms'], 'mobile', ['sms_url' => 'http://yourapp.io/sms']);

print_r($result);
```

### <a id="deallocate-number"></a> Deallocate number

Deallocate a virtual phone number

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$number = $Php46ElksClient->number();

$result = $number->deallocate('enter id', 'yes');

print_r($result);
```

### <a id="configure-number"></a> Configure number

Configure a virtual phone number

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$number = $Php46ElksClient->number();

$result = $number->configure('enter id', ['voice_start' => 'http://yourapp.io/voice']);

print_r($result);
```


### <a id="get-all"></a> Get all

Show virtual phone number information

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$number = $Php46ElksClient->number();

$result = $number->get();

// print results
print_r($result->getData());

// get next page
print_r($result->getNext());
```

### <a id="get-by-id"></a> Get by id

Show virtual phone number information

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$number = $Php46ElksClient->number();

$result = $number->getById('enter id');

print_r($result);
```
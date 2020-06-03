# The account Client

The Phone client wraps the account section of [46elks.se docs](https://46elks.se/docs/get-account)

## Available services

Unlike [Sms](sms.md) and [Phone calls](call.md) no sub services exist for the account client.

* [Accessing the account client](#accessing-the-account-client)
* [Get account info](#get)
* [Update account info](#update)


### <a id="accessing-the-account-client"></a> Access the client

The account client may be accessed like this

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$account = $Php46ElksClient->account();
```

### <a id="get"></a> Get accoint info

Get account information

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$account = $Php46ElksClient->account();

$result = $account->get(); // ->me(); also works

print_r($result);
```

### <a id="update"></a> Update account info

Update account information

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$account = $Php46ElksClient->account();

$result = $account->update(5000);

print_r($result);
```

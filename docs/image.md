# The image Client

The Image client wraps the image section of [46elks.se docs](https://46elks.se/docs/get-images)

## Available services

Unlike [Sms](sms.md) and [Phone calls](call.md) no sub services exist for the image client.

* [Accessing the image client](#accessing-the-image-client)
* [Get images](#get)
* [Get images by id](#get-by-id)
* [Get image file](#get-file-by-id)


### <a id="accessing-the-image-client"></a> Access the client

The image client may be accessed like this

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$image = $Php46ElksClient->image();
```

### <a id="get"></a> Get images

Get MMS Image History

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$image = $Php46ElksClient->image();

$result = $image->get(); 

print_r($result);
```

### <a id="get-by-id"></a> Get image by id

Get MMS Image by ID

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$image = $Php46ElksClient->image();

$result = $image->getById('enter id here'); 

print_r($result);
```


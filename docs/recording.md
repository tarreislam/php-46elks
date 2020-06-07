# The Recording Client

The Recording client wraps the recording section of [46elks.se docs](https://46elks.se/docs/get-recordings)

## Available services

Unlike [Sms](sms.md) and [Phone calls](call.md) no sub services exist for the recording client.

* [Accessing the recording client](#accessing-the-recording-client)
* [Get recordings](#get)
* [Get recordings by id](#get-by-id)
* [Get recording file](#get-file-by-id)


### <a id="accessing-the-recording-client"></a> Access the client

The recording client may be accessed like this

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$recording = $Php46ElksClient->recording();
```

### <a id="get"></a> Get recordings

Get recording History

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$recording = $Php46ElksClient->recording();

$result = $recording->get(); 

print_r($result);
```

### <a id="get-by-id"></a> Get recording by id

Get recording by ID

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$recording = $Php46ElksClient->recording();

$result = $recording->getById('enter id here'); 

print_r($result);
```


### <a id="get-file-by-id"></a> Get recording file by id

Returns the recording file of the recording with the specified ID.

```php
// Initialize client
use Tarre\Php46Elks\Client as Php46ElkClient;
$Php46ElksClient = new Php46ElkClient('username', 'password');

$recording = $Php46ElksClient->recording();

$result = $recording->getFileById('enter id here'); 

// You can either save the file to your disk
$result->saveToDisk('/var/downloads', 'my-file.mp3');

// or get the raw recording data
$result->getContent();
```


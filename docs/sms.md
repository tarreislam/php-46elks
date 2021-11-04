# SMS

### Sending SMS

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
    ->setDryRun('yes')
    ->setFlash('yes')
    ->setDontLog('message')
    ->setWhenDelivered('https://myapp.com')
    ->setMessage('Hello')
    ->setTo('+46701474417,+46701474417'); // Also works
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

### Callbacks / Webhooks

> Note, this code must be available to the internetz

#### Receiving sms

```php
use Tarre\Php46Elks\Elks\Sms\Responses\DeliveryReportResponse;
use Tarre\Php46Elks\Elks\Sms\Responses\ReceivedSmsResponse;
use Tarre\Php46Elks\Elks\Sms\SmsReceiver;

$receiver = new SmsReceiver;

$receiver->sms(function (ReceivedSmsResponse $response) {

    var_dump([
        $response->getId(),
        $response->getTo(),
        $response->getFrom(),
        $response->getMessage(),
        $response->getCreated(),
        $response->getDirection()
    ]);

    /*
     * Yields array representation with reply
     */
    return $response->forward('+46701474417');
});
```

#### Delivery reports

```php
use Tarre\Php46Elks\Elks\Sms\Responses\DeliveryReportResponse;
use Tarre\Php46Elks\Elks\Sms\Responses\ReceivedSmsResponse;
use Tarre\Php46Elks\Elks\Sms\SmsReceiver;

$receiver = new SmsReceiver;

$receiver->dlr(function (DeliveryReportResponse $response) {
    var_dump([
        $response->getId(),
        $response->getDelivered(),
        $response->getStatus(),
    ]);
});

```

### History

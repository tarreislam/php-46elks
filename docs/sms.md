# The SMS client

The SMS client wraps the SMS section of [46elks.se docs](https://46elks.se/docs/send-sms)

## Available services

* [Dispatcher (SMS & MMS)](#dispatcher)
    * [Send an SMS](#send-an-sms)
    * [Send an MMS](#send-an-mms)
    * [Multiple recipients](#multiple-recipients)
    * [Lines and content](#lines-and-content)
    * [Flash messages / Push notifications](#flash-messages-push-notifications)
    * [Delivery webhooks](#sms-webhooks)
* [Delivery reports (DLRs)](#dlr)
* [Receiver](#receiver)
    * [Receive an SMS or MMS](#receive-an-sms-or-mms)
    * [Reply to SMS](#reply-to-sms)
    * [Forward SMS](#forward-sms)
* [History](#history)
    * [Get all](#get-all)
    * [Filter requests](#filter-requests)
    * [Get single](#get-single)


## <a id="dispatcher"></a> Dispatcher

The dispatcher service handles outgoing SMS and MMS. You can access the services in a couple of ways.

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');
// Here are some ways you can access the SMS dispatcher
$SMS = $Php46ElksClient->sms()->SMSDispatcher();
$SMS = $Php46ElksClient->sms()->dispatcher(); // default is sms
$SMS = $Php46ElksClient->sms()->dispatcher('sms');
// for MMS
$MMS = $Php46ElksClient->sms()->MMSDispatcher();
$MMS = $Php46ElksClient->sms()->dispatcher('mms'); // default is sms
```

### <a id="send-an-sms"></a> Send an SMS

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$SMS = $Php46ElksClient->sms()->SMSDispatcher();

$SMS
    ->from('Php46Elks')
    ->recipient('+467EnterYourNumberHere')
    ->content('This is a text message!')
    ->send();
```

### <a id="send-an-mms"></a> Send an MMS
```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$MMS = $Php46ElksClient->sms()->MMSDispatcher();

$MMS
    ->from('<valid number for MMS>')
    ->recipient('+467yourNumber')
    ->image('https://i.imgur.com/M8XL4Wq.png')
    ->content('Optional text')
    ->send();
```

### <a id="multiple-recipients"></a> Send to multiple recipients

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$SMS = $Php46ElksClient->sms()->SMSDispatcher();

$SMS
    ->from('Php46Elks') 
    ->recipient('+46Number1')
    ->recipient('+46Number2')
    ->recipient('+46Number3')
    ->content('Hello!')
    ->send();

// alternative way

$SMS
    ->from('Php46Elks') 
    ->setRecipients(['+46Number1', '+46Number2', '+46Number3'])
    ->content('Hello!')
    ->send();
```

### <a id="lines-and-content"></a> Lines and content

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$SMS = $Php46ElksClient->sms()->SMSDispatcher();

$SMS
    ->from('Php46Elks') 
    ->setRecipients(['+46Number1', '+46Number2', '+46Number3'])
    ->line('First line')
    ->line('Second line')
    ->line('')// new line
    ->line('Fourth line')
    ->send();

// alternative way

$SMS
    ->from('Php46Elks') 
    ->setRecipients(['+46Number1', '+46Number2', '+46Number3'])
    ->setLines([
        'First line',
        'Second line',
        '', // new line
        'Fourth line'
    ])  
    ->send();
```

### <a id="flash-messages-push-notifications"></a>Send flash messages / Push notifications

**This feature only works for SMS**

Flash messages will be displayed immediately upon arrival and not stored.

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$SMS = $Php46ElksClient->sms()->SMSDispatcher();

$SMS
    ->from('Php46Elks') 
    ->flash()
    ->recipient('+46number')
    ->line('Hello')
    ->line('Here is your code')
    ->line('ABC123')
    ->send();
```

### <a id="sms-webhooks"></a> Delivery webhooks

To receive status updates for your sent SMS or MMS, you want to use the [whendelivered](https://46elks.se/docs/sms-delivery-reports) option to your messages. To handle incoming DLRs check out the next chapter [Delivery reports (DLRs)](dlr) 

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$SMS = $Php46ElksClient->sms()->SMSDispatcher();

$SMS
    ->from('Php46Elks') 
    ->recipient('+46number')
    ->line('Hello did you receive this message?')
    ->whenDelivered('https://yourapp.io/dlr') // This webhook URL will receive a POST request every time the delivery status changes. See "Receive delivery reports" to handle these events
    ->send();
```

## <a id="dlr"></a> Delivery reports (DLRs)

The DLR service handles SMS and MMS [SMS Delivery Reports (DLRs)](https://46elks.se/docs/sms-delivery-reports).

Because handling DLRs does not require authentication to 46elks API, we could access this service in two different ways.

```php
use Tarre\Php46Elks\Clients\SMS\Services\SMSDLRService;
use Tarre\Php46Elks\Client as Php46ElkClient;

// Via the Php46ElkClient
$Php46ElksClient = new Php46ElkClient('username', 'password');
$dlr = $Php46ElksClient->sms()->dlr();

// Without using the client
$dlr = new SMSDLRService;
```

The is done for convenience sake. All examples will be using the first method to maintain continuity.

### Receive delivery reports


```php
use Tarre\Php46Elks\Clients\SMS\Resources\DeliveryReport;
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$dlr = $Php46ElksClient->sms()->dlr();

$dlr->handleRequest(function (DeliveryReport $SMS) {
    print_r([
        'id' => $SMS->id(),
        'status' => $SMS->status(),
        'delivered' => $SMS->delivered(),
    ]);
});
```

_Notes_

* `$_REQUEST` is used to fetch the request data that 46elks sends, it can be overridden by passing another `array` as the second parameter of `handleRequest`

## <a id="receiver"></a> Receiver

The receiver service handles incoming SMS and MMS [sent by 46elks](https://46elks.se/docs/receive-sms).

Because receiving messages does not require authentication to 46elks API, we could access this service in two different ways.

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
use Tarre\Php46Elks\Clients\SMS\Services\SMSReceiverService;

// Via the Php46ElkClient
$Php46ElksClient = new Php46ElkClient('username', 'password');
$receiver = $Php46ElksClient->sms()->receiver();

// Without using the client
$receiver = new SMSReceiverService;
```

The is done for convenience sake. All examples will be using the first method to maintain continuity.

### <a id="receive-an-sms-or-mms"></a> Receive an SMS or MMS

* `$_REQUEST` is used to handle the request data that 46elks sends, it can be overridden by passing another key->val `array` as the second parameter of `handleRequest`
* If the `Message` is an SMS you can reply to the sender by returning `$SMS->reply('Thanks for the text!')`

```php
use Tarre\Php46Elks\Clients\SMS\Resources\Message;
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$receiver = $Php46ElksClient->sms()->receiver();

$receiver->handleRequest(function (Message $SMS) {
    print_r([
        'direction' => $SMS->direction(),
        'id' => $SMS->id(),
        'from' => $SMS->from(),
        'to' => $SMS->to(),
        'created' => $SMS->created(),
        'message' => $SMS->message(),
        'images' => $SMS->images(),
        'cost' => $SMS->cost() 
    ]);
});
```

### <a id="reply-to-sms"></a> Reply to SMS

Instead of using the [SMS dispatcher](#send-an-sms) to reply to the message. You could simply return `$SMS->reply('text')` to reply to the sender

```php
use Tarre\Php46Elks\Clients\SMS\Resources\Message;
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$receiver = $Php46ElksClient->sms()->receiver();

$receiver->handleRequest(function (Message $SMS) {
    // ... 
    return $SMS->reply('Thank you!');
});
```

### <a id="forward-sms"></a> Forward SMS

Besides replying to SMS you could also forward the message. With optional suffixes and prefixes

```php
use Tarre\Php46Elks\Clients\SMS\Resources\Message;
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$receiver = $Php46ElksClient->sms()->receiver();

$receiver->handleRequest(function (Message $SMS) {
    // ... 
    return $SMS->forward('+46928398213', 'This message was forwarded: ', 'Have a nice day!');
});
```

## <a id="history"></a> History

This is how you fetch [SMS history](https://46elks.se/docs/sms-history) from 46elks.

_Notes_
* Log retention is set in your account settings

### <a id="get-all"></a> Get all history

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$history = $Php46ElksClient->sms()->history('sms'); // sms or mms

$paginator = $history->get(); 

foreach($paginator->getData() as $SMS){
    print_r([
        'direction' => $SMS->direction(),
        'id' => $SMS->id(),
        'from' => $SMS->from(),
        'to' => $SMS->to(),
        'created' => $SMS->created(),
        'message' => $SMS->message(),
        'images' => $SMS->images(),
        'cost' => $SMS->cost() 
    ]);
}
```

### <a id="filter"></a> Filter requests

There exists a couple of filter settings you can use to search and narrow your results

* `start($date)` Retrieve SMS before this date.
* `end($date)` Retrieve SMS after this date.
* `limit($number)` Limit the number of results on each page.
* `to($e164Number)` Filter on recipient.

These filters _MUST_ to be used before `get` is invoked. After `get` has been invoked, you can use `next()` to get the next "page"

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$history = $Php46ElksClient->sms()->history('sms'); // sms or mms

$paginator = $history
    ->to('+467012232343')
    ->start('2020-02-14T09:52:07.302000')
    ->end('2020-02-15T09:52:07.302000')
    ->get(); 

foreach($paginator->getData() as $SMS){
    print_r([
        'direction' => $SMS->direction(),
        'id' => $SMS->id(),
        'from' => $SMS->from(),
        'to' => $SMS->to(),
        'created' => $SMS->created(),
        'message' => $SMS->message(),
        'images' => $SMS->images(),
        'cost' => $SMS->cost() 
    ]);
}
```

### <a id="get-single"></a> Get single

To retrieve a single resource you can invoke the `getById` instead of `get`

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$history = $Php46ElksClient->sms()->history('sms'); // sms or mms

$SMS = $history->getById('enter sms or MMS id here');

print_r([
    'direction' => $SMS->direction(),
    'id' => $SMS->id(),
    'from' => $SMS->from(),
    'to' => $SMS->to(),
    'created' => $SMS->created(),
    'message' => $SMS->message(),
    'images' => $SMS->images(),
    'cost' => $SMS->cost() 
]);
```

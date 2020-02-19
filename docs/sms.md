# The SMS client


### Available services

Click on the service to read more

* [Dispatcher (SMS & MMS)](#dispatcher)
    * **Examples**
    * [Send an SMS](#send-an-sms)
    * [Send an MMS](#send-an-mms)
    * [Multiple recipients](#multiple-recipients)
    * [Lines and content](#lines-and-content)
    * [Flash messages / Push notifications](#flash-messages-push-notifications)
* [Receiver](#receiver)
* [DLR Receiver](#dlr)
* [History](#history)


### <a id="dispatcher"></a> Dispatcher

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


### <a id="receiver"></a> Receiver

### Receive an SMS or MMS

This method must be exposed to `46elks.se` webhooks to work.

* `$_REQUEST` is used to handle the request data that 46elks sends, it can be overridden by passing another key->val `array` as the second parameter of `handleRequest`
* If the `MessageResource` is an SMS you can reply to the sender by returning `$SMS->reply('Thanks for the text!')`

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

### Delivery reports and events

> This webhook URL will receive a POST request every time the delivery status changes. See "Receive delivery reports" to handle these events

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$SMS = $Php46ElksClient->sms()->SMSDispatcher();

$SMS
    ->from('Php46Elks') 
    ->recipient('+46number')
    ->line('Hello did you receive this message?')
    ->whenDelivered('https://yourapp.io/dlr')
    ->send();
```

### Receive delivery reports


* `$_REQUEST` is used to handle the request data that 46elks sends, it can be overridden by passing another key->val `array` as the second parameter of `handleRequest`
* If the `MessageResource` is an SMS you can reply to the sender by returning `$SMS->reply('Thanks for the text!')`

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

### Message history

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

// get by id

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

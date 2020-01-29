### Send an SMS
```php
use Tarre\Php46Elks\Client;

$Php46ElksClient = (new Client('username', 'password'));

$SMS = $Php46ElksClient->sms()->SMSDispatcher();


$SMS
    ->from('Php46Elks')
    ->recipient('+467EnterYourNumberHere')
    ->content('This is a text message!')
    ->send();
```

### Send an MMS
```php
use Tarre\Php46Elks\Client;

$Php46ElksClient = (new Client('username', 'password'));

$MMS = $Php46ElksClient->sms()->MMSDispatcher();

$MMS
    ->from('<valid number for MMS>')
    ->recipient('+467yourNumber')
    ->image('https://i.imgur.com/M8XL4Wq.png')
    ->content('Optional text')
    ->send();
```

### Receive an SMS or MMS

This method must be exposed to `46elks.se` webhooks to work.

* `$_REQUEST` is used to handle the request data that 46elks sends, it can be overridden by passing another key->val `array` as the second parameter of `handleRequest`
* If the `MessageResource` is an SMS you can reply to the sender by returning `$SMS->reply('Thanks for the text!')`

```php
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\SMS\Resources\MessageResource;

$Php46ElksClient = (new Client('username', 'password'));

$receiver = $Php46ElksClient->sms()->receiver();

$receiver->handleRequest(function (MessageResource $SMS) {
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

### Send to multiple recipients

```php
use Tarre\Php46Elks\Client;

$Php46ElksClient = (new Client('username', 'password'));

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

### Set content as lines instead of text

```php
use Tarre\Php46Elks\Client;

$Php46ElksClient = (new Client('username', 'password'));

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

### Send flash messages / "push notifications"

> The message will be displayed immediately upon arrival and not stored.

```php
use Tarre\Php46Elks\Client;

$Php46ElksClient = (new Client('username', 'password'));

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

### Delivery reports and events

> This webhook URL will receive a POST request every time the delivery status changes. See "Receive delivery reports" to handle these events

```php
use Tarre\Php46Elks\Client;

$Php46ElksClient = (new Client('username', 'password'));

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
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\SMS\Resources\DeliveryReportResource;

$Php46ElksClient = (new Client('username', 'password'));


$dlr = $Php46ElksClient->sms()->dlr();

$dlr->handleRequest(function (DeliveryReportResource $SMS) {
    print_r([
        'id' => $SMS->id(),
        'status' => $SMS->status(),
        'delivered' => $SMS->delivered(),
    ]);

});
```

### Message history

```php
use Tarre\Php46Elks\Client;

$Php46ElksClient = (new Client('username', 'password'));



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

# The Phone client

The Phone client wraps the phone section of [46elks.se docs](https://46elks.se/docs/make-call)

## Available services

* [Dispatcher](#dispatcher)
    * [Make a phone call](#make-a-phone-call)
    * [Multiple recipients](#multiple-recipients)
* [Receiver](#receiver)
    * [Receive a phone call](#receive-phone-calls)
* [Phone actions (Incoming and outgoing)](#phone-actions)
    * [Action: Connect](#action-connect)
    * [Action: Play](#action-play)
    * [Action: IVR](#action-ivr)
    * [Action: Record](#action-record)
    * [Action: Recordcall](#action-record-call)
    * [Action: Hangup](#action-hangup)
* [Phone router (Incoming and outgoing)](#phone-router)
    * [Basic example](#router-basics)
    * [Caching routes as json "Compiling"](#router-compiler)
* [History](#history)
   * [Get all](#get-all)
   * [Filter requests](#filter-requests)
   * [Get single](#get-single)
## <a id="dispatcher"></a> Dispatcher

The dispatcher service handles outgoing phone calls. This is how you access the phone dispatcher

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$phone = $Php46ElksClient->phone()->dispatcher();
```

### <a id="make-a-phone-call"></a> Make a phone call

To make an outgoing phone call you need a valid number with `VOICE` support, you can get this number from 46elks control panel.

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$phone = $Php46ElksClient->phone()->dispatcher();

$phone
    // set outgoing number (Must be a valid VOICE enabled e164 number from 46elks dashboard)
    ->from('+467147147417')
    // Set recipeient
    ->recipient('+4671928398')
    // When the accepts the call, play sound file
    ->voiceStart(function(PhoneCallAction $action){
        return $action->play('http://yourapp.com/wav/hello.wav');
});
```

### <a id="multiple-recipients"></a> Multiple recipients

You can use the `setRecipients` method to make multiple outgoing calls in one fell swoop, all calls will be async.

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$phone = $Php46ElksClient->phone()->dispatcher();

$phone
    // set outgoing number (Must be a valid VOICE enabled e164 number from 46elks dashboard)
    ->from('+467147147417')
    // Set recipeient(s)
    ->setRecipients([
        '+46719283981',
        '+46719283982',
        '+46719283983',
        '+46719283984',
])
    // When the accepts the call, play a sound file
    ->voiceStart(function(PhoneCallAction $action){
        return $action->play('http://yourapp.com/wav/hello.wav');
});
```

## <a id="receiver"></a> Receiving phone calls

The receiver service handles incoming phone calls. This is how you access the phone receiver

```php
// Via base client
use Tarre\Php46Elks\Client as Php46ElkClient;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');
// With client
$phone = $Php46ElksClient->phone()->receiver();
// Without client
$phone = new PhoneCallReceiverService;
```

**NOTE:** Receiving phone calls requires your PHP application to be exposed to 46elks.com`s webhooks.

### <a id="receive-phone-calls"></a> Receive a phone call

Receiving phone calls is pretty straight forward, 46elks.com sends an `POST` request to the web hook to the url you provided in their control panel.

When your app receives the message, you can read certain data and peform some actions. 

```php
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;

$Php46ElksClient = (new Client('username', 'password'));

$receiver = $Php46ElksClient->phone()->receiver();

return $receiver->handleRequest(function(ReceivedPhoneCall $phoneCall){
    
    // You wont be able to see this print_r result, so replace it with some log function of yours
    print_r([
        $phoneCall->callId(),
        $phoneCall->created(),
        $phoneCall->direction(),
        $phoneCall->from(),
        $phoneCall->to() 
    ]);

    // You wont be able to see this print_r result, so replace it with some log function of yours
    print_r($phoneCall->toArray());

    // handle the phone call by playing a soundfile, then hanging up
    return $phoneCall
        ->action() 
        ->play('http://yourapp.com/elks/soundfile.mp3') // play this file
});
```

## <a id="phone-actions"></a> Phone actions (Incoming and outgoing)

Whether you are making outgoing calls or receiving incoming calls, the `PhoneCallAction` class can be accessed to peform certains actions to the active call.

All examples will be presented as received calls without the base client

* [Connect](#action-connect)
* [Play](#action-play)
* [IVR](#action-ivr)
* [Record](#action-record)
* [Recordcall](#action-record-call)
* [Hangup](#action-hangup)

### <a id="action-connect"></a> Action: connect

Connect the call to a given number, and in the case of an answer, let the two callers speak to each other.

```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action()
        ->connect('+46701464412');
});
```

### <a id="action-play"></a> Action: play

Play an audio file or predefined sound. Could be either a URL on your server or a sound resource provided by 46elks.

```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action()
        ->play('http://yourapp.com/welcome.mp3'); 
});
```

### <a id="action-ivr"></a> Action: IVR

The “ivr” action fills the purpose of playing a sound resource while also retrieving digits pressed by the caller (think customer support menus etc.).
Once a key is pressed the url in `next` will recieive a post request with an query param called `result` with the key pressed, so if the `3` key was pressed the url would end up like this  `http://yourapp.com/ivr-test.php?result=3`

```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action() // access phone call actions
        ->ivr('welcome.mp3')
        ->next('http://yourapp.com/ivr-test.php'); // go back to the same url. Effectively a loop
});
```

You can also supply phone actions for the different alternatives directly.

```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action() // access phone call actions
        ->ivr([ 
                    'ivr' => 'welcome.mp3', //play this when call is connected
                    '1' => $phoneCall->action()->play('hours.mp3'), // if the caller presses 1, play opening hours
                    '2' => $phoneCall->action()->connect('+467014674527'), // if the caller presses 2, connect them to customer service
                ])
        ->next('http://yourapp.com/ivr-test.php'); // repeat
});
```

### <a id="action-record"></a> Action: Record 

This action records the voice of the caller.

```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action() // access phone call actions
        ->recordCall('https://yourapp.example/elks/recordings')
        ->connect('+46780121241')// Or any other action
        ->next('https://yourapp.example/elks/calls'); // optional
});
```

### <a id="action-record-call"></a> Action: Record call 

This action records the entire call and sends out a webhook with a link to the recording when the call ends. This action cannot be used by itself, it triggers at the same time as another action.
```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action() // access phone call actions
        ->record('https://yourapp.example/elks/recordings')
        ->next('https://yourapp.example/elks/calls'); // optional
});
```

### <a id="action-hangup"></a> Action: Hangup 

End the call. If this is your first action, it is possible to control signalling, otherwise only "reject" is allowed.

```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action() // access phone call actions
        ->play('https://yourapp.example/elks/recordings')
        ->hangUp('reject'); // optional
});
```

To learn more about call actions, please consult [this](https://46elks.com/docs/call-actions) portion of the documentation. All call actions are reflected as functions via `PhoneCallAction`

## <a id="phone-router"></a> Phone router

The phone router handles incoming requests.

<p align="center">
<img src="https://i.imgur.com/1X0hr9F.jpg">
</p>

### <a id="router-basics"></a> Basic example

**This is the scenario:**
> When you dial +46xxxxx
> You are presented with a soundfile telling you which buttons do what:
> *Press 1 for hours*
> *Press 2 to talk with an employee*
> Option 1 just plays a soundfile then hangs up
> Option 2 connets the caller to another number

> Assuming this code exists in `http://yourapp.com/elks/calls` and `voice_start` @ 46elks.com points to `http://yourapp.com/elks/calls`

```php
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;

/* Set the baseURL for all actions and webhooks (Optional) */
Client::setResourceBaseUrl('http://yourapp.com/elks/calls');

$Php46ElksClient = (new Client('username', 'password'));

$router = $Php46ElksClient->phone()->router();

$router->default(function(PhoneCallAction $action){
    return $action
        ->ivr('choices.mp3')
        ->next('', ['action' => 'ivr']); // This will create a loopback to this action again
});

$router->register('ivr1', function(PhoneCallAction $action){
    return $action
        ->play('hours.mp3') // opening hours will be presented
        ->next('', ['action' => 'ivr']); // This will redirect the call to "http://yourapp.com/elks/calls.php?action=ivr"
});

$router->register('ivr2', function(PhoneCallAction $action){
    return $action
        ->connect('+467013212321');
});

// At first glance, this looks weird. But it makes sense.
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$digits = isset($_REQUEST['digits']) ? $_REQUEST['digits'] : '';
// As you saw above this example, we assumed the calls initial endpoint is `.../calls`
// And according to https://46elks.se/docs/voice-ivr the IVR action will make a request with an added query param called `digits`
// That means that when the user inputs 1 on their phone, 46elks will make this request `calls.php?action=ivr&digits=1`
// Therefore we can match those 

// Handle registered routes. This action will return a JSON that 46elks reads.
$actionName = $action . $digits;

return $router->handle($actionName);
```

### <a id="router-compiler"></a> Caching routes as json "Compiling"

if you want to save different routes dynamically you can save and load routes with `compile`

```php
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;

/* Set the baseURL for all actions and webhooks (Optional) */
Client::setResourceBaseUrl('http://yourapp.com/elks/calls');

$Php46ElksClient = (new Client('username', 'password'));

$router = $Php46ElksClient->phone()->router();

$router->default(function(PhoneCallAction $action){
    return $action
        ->ivr('choices.mp3')
        ->next('', ['action' => 'ivr']); // This will create a loopback to this action again
});

// save 
$router->compile();

$compiledRoutes = $router->toJson();

// load json
$router->compile(json_decode($compiledRoutes, true));

```

## <a id="history"></a> History

This is how you fetch [call history](https://46elks.se/docs/call-history) from 46elks.

_Notes_
* Log retention is set in your account settings

### <a id="get-all"></a> Get all history

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$history = $Php46ElksClient->phoneCall()->history();

$paginator = $history->get(); 

foreach($paginator->getData() as $PhoneCall){
    print_r([
        'direction' => $PhoneCall->direction(),
        'id' => $PhoneCall->id(),
        'from' => $PhoneCall->from(),
        'to' => $PhoneCall->to(),
        'created' => $PhoneCall->created(),
        'cost' => $PhoneCall->cost() 
    ]);
}
```

### <a id="filter"></a> Filter requests

There exists a couple of filter settings you can use to search and narrow your results

* `start($date)` Retrieve PhoneCall before this date.
* `end($date)` Retrieve PhoneCall after this date.
* `limit($number)` Limit the number of results on each page.

These filters _MUST_ to be used before `get` is invoked. After `get` has been invoked, you can use `next()` to get the next "page"

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$history = $Php46ElksClient->phone()->history(); 

$paginator = $history
    ->start('2020-02-14T09:52:07.302000')
    ->end('2020-02-15T09:52:07.302000')
    ->get(); 

foreach($paginator->getData() as $PhoneCall){
    print_r([
        'direction' => $PhoneCall->direction(),
        'id' => $PhoneCall->id(),
        'from' => $PhoneCall->from(),
        'to' => $PhoneCall->to(),
        'created' => $PhoneCall->created(),
        'cost' => $PhoneCall->cost() 
    ]);
}
```

### <a id="get-single"></a> Get single

To retrieve a single resource you can invoke the `getById` instead of `get`

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$history = $Php46ElksClient->phone()->history();

$PhoneCall = $history->getById('enter phoneCall id here');

print_r([
    'direction' => $PhoneCall->direction(),
    'id' => $PhoneCall->id(),
    'from' => $PhoneCall->from(),
    'to' => $PhoneCall->to(),
    'created' => $PhoneCall->created(),
    'cost' => $PhoneCall->cost() 
]);
```

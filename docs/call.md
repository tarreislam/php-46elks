# The Phone client

The Phone client wraps the phone section of [46elks.se docs](https://46elks.se/docs/make-call)

## Available services

* [Dispatcher](#dispatcher)
    * [Make a phone call](#make-a-phone-call)
    * [Multiple recipients](#multiple-recipients)
* [Receiver](#receiver)
    * [Receive a phone call](#receive-phone-calls)
* [Phone actions (Incoming and outgoing)](#phone-actions)
* [Phone router (Incoming and outgoing)](#phone-router)
* [History](#history)

## <a id="dispatcher"></a> Dispatcher

The dispatcher service handles outgoing phone calls. This is how you access the phone dispatcher

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$phone = $Php46ElksClient->phone()->dispatcher();
```

### <a id="make-a-phone-call"></a> Make a phone call

To make an outgoing phonecall you need a valid number with `VOICE` support, you can get this number from 46elks control panel.

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
    // When the accepts the call, play a file and hang up
    ->voiceStart(function(PhoneCallAction $action){
        return $action
            ->play('http://myapp.com/wav/hello.wav')
            ->hangUp();
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
    // When the accepts the call, play a file and hang up
    ->voiceStart(function(PhoneCallAction $action){
        return $action
            ->play('http://myapp.com/wav/hello.wav')
            ->hangUp();
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

$phone = $Php46ElksClient->phone()->receiver();

// without client
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
        ->hangUp(); // hangup the call
});
```


## <a id="phone-actions"></a> Phone actions (Incoming and outgoing)

Whether you are making outgoing calls or receiving incoming calls, the `PhoneCallAction` class can be accessed to peform certains actions to the active call.

All examples will be presented as received calls without the base client

**connect** _Connect the call to a given number, and in the case of an answer, let the two callers speak to each other._
```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action() // access phone call actions
        ->connect('+46701464412'); // connect the caller and this
});
```
**ivr** _The “ivr” action fills the purpose of playing a sound resource while also retrieving digits pressed by the caller (think customer support menus etc.)_

```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action() // access phone call actions
        ->ivr('welcome.mp3')
        ->next('http://yourapp.com/ivr-test.php'); // go back to the same url. Effectivly creates a loop
});
```

**advanced ivr** _You can do some more fancy stuff by assigning actions to different keys_

```php
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;
use Tarre\Php46Elks\Clients\PhoneCall\Services\PhoneCallReceiverService;

return (new PhoneCallReceiverService)->handleRequest(function(ReceivedPhoneCall $phoneCall){

    return $phoneCall
        ->action() // access phone call actions
        ->ivr([
                    'ivr' => 'welcome.mp3', //
                    '1' => $phoneCall->action()->play('opend-hours.mp3'), // play opening hours
                    '2' => $phoneCall->action()->connect('+467014674527'), // connect to customer service
                ])
        ->next('http://yourapp.com/ivr-test.php'); // go back to the same url. Effectivly creates a loop
});
```


To learn more about call actions, please consult [this](https://46elks.com/docs/call-actions) portion of the documentation. All call actions are reflected as functions via; `PhoneCallAction`





## Using the router

Sometimes you want to do more. In those cases you could use the phone router service. Unlike the receiver service, the router service is used to handle multiple actions in a simple way

**This is the scenario:**
> When you dial +46xxxxx
> You are presented with a soundfile telling you which buttons do what:
> *Press 1 for hours*
> *Press 2 to talk with an employee*
> Option 1 just plays a soundfile then hangs up
> Option 2 connets the caller to another number


> Assuming this code exists in `http://yourapp.com/elks/calls.php` and `voice_start` @ 46elks.com points to `http://yourapp.com/elks/calls.php?action=ivr`


```php
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;

/* Set the baseURL for all actions and webhooks */
Client::setResourceBaseUrl('http://yourapp.com/elks/calls.php');

$Php46ElksClient = (new Client('username', 'password'));

$router = $Php46ElksClient->phone()->router();

$router->register('ivr', function(PhoneCallAction $action){
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
// As you saw above this example, we assumed the calls initial endpoint is `calls.php?action=ivr`
// And according to https://46elks.se/docs/voice-ivr the IVR action will make a request with an added query param called `digits`
// That means that when the user inputs 1 on their phone, 46elks will make this request `calls.php?action=ivr&digits=1`
// Therefore we can match those 

// Handle registered routes. This action will return a JSON that 46elks reads.
$actionName = $action . $digits;

return $router->handle($actionName);
```

TODO: more examples on receving phone calls and using actions standalone in apps that already have an router.

## Dispatching phone calls

TODO



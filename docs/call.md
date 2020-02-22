# The Phone client

The Phone client wraps the phone section of [46elks.se docs](https://46elks.se/docs/make-call)

#### Available services
* [Dispatcher](#dispatcher)
    * [Make a phone call](#make-a-phone-call)
* [Receiver](#receiver)
* [Router](#router)
* [Dispatcher](#dispatcher)
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

When you make a phone call

```php
use Tarre\Php46Elks\Client as Php46ElkClient;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;
// Initialize client
$Php46ElksClient = new Php46ElkClient('username', 'password');

$phone = $Php46ElksClient->phone()->dispatcher();

$phone
    // Set recipeient(s)
    ->recipient('+4671928398')
    // Play a file
    ->voiceStart(function(PhoneCallAction $action){
        return $action
            ->play('http://myapp.com/wav/hello.wav')
            ->hangUp();
});
```



-------- not done --------

## Receiving phone calls

In this section I will try to show how you can handle incoming phone calls and utilize 46elks full potential

#### Lifecycle 

The lifecycle is straight forward

```
1.  PEER dials 46elks number
2.  46elks makes an request to your application via an webhook provided in the 46elks.com interface
3.  Your application returns a simple json response with the next instruction or `action` as its called
```

#### Handle your first incoming phone call

In this example we receive an phone call

1. Play a soundfile
2. Hangup

```php
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;

$Php46ElksClient = (new Client('username', 'password'));

$receiver = $Php46ElksClient->phone()->receiver();

return $receiver->handleRequest(function(ReceivedPhoneCall $phoneCall){
    
    return $phoneCall
        ->action() 
        ->play('http://yourapp.com/elks/soundfile.mp3') // play this file
        ->hangUp(); // hangup the call
});
```

#### Incoming phone call details

Besides performing `actions` you could also acquire information about the call via the `ReceivedPhoneCall` class

```php
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;

$Php46ElksClient = (new Client('username', 'password'));

$receiver = $Php46ElksClient->phone()->receiver();

return $receiver->handleRequest(function(ReceivedPhoneCall $phoneCall){
    
    print_r([
        $phoneCall->callId(),
        $phoneCall->direction(),
        $phoneCall->created(),
        $phoneCall->from(),
        $phoneCall->to(),
    ]);
   
    return $phoneCall
        ->action() 
        ->play('http://yourapp.com/elks/soundfile.mp3')
        ->hangUp(); // hangup the call
});
```

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



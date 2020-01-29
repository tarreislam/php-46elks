### Introduction

Unlike other PBX providers 46elks.com lets your application handle incomming and outgoing calls thru HTTP requests, that means you can pretty much integrate it to any programming language with ez.

#### Lifecycle 

```
1.  PEER dials 46elks number
2.  46elks makes an request to your application via an webhook
3.  Your application returns a simple json response with the next instruction (IE play soundfile, hangup, record, voicemail, go to next route)
```
![46elks app](https://i.imgur.com/4Ds4xNL.png)

### Handle your first incoming call

> Assuming this code exists in `http://yourapp.com/elks/calls.php` or something similar accessable to 46elks.com
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

### The router

Sometimes you want to do more than just play a soundfile and hangup. In those cases you could use the phone router. Unlike the receiver, the router is used to perform multiple actions in the same call.

For example, you want a soundfile


```php
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;

$Php46ElksClient = (new Client('username', 'password'));

$router = $Php46ElksClient->phone()->router();

$router->register('opening-hours', function(PhoneCallAction $action){
    return $action
        ->play('hours.mp3')
        ->ivr('next');
});
```

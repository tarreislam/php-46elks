### Introduction

Unlike other PBX providers 46elks.com lets your application handle incomming and outgoing calls thru HTTP requests, that means you can pretty much integrate it to any programming language with ez.

#### Lifecycle 

```
1.  PEER dials 46elks number
2.  46elks makes an request to your application via an webhook
3.  Your application returns a simple json response with the next instruction (IE play soundfile, hangup, record, voicemail, go to next route)
```
![46elks app](https://i.imgur.com/4Ds4xNL.png)

### Before we begin

### Handle your first incomming call

This is a pretty basic example showing you how 

> Assuming this code exists in http://yourapp.com/elks/calls.php or whatever
```php
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;

$Php46ElksClient = (new Client('username', 'password'));

$receiver = $Php46ElksClient->phone()->receiver();

return $receiver->handleRequest(function(ReceivedPhoneCall $phoneCall){
    
    return $phoneCall->router()->
});
```

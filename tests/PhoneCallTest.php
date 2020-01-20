<?php

use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;


class PhoneCallTest extends TestCase
{

    public function testPhoneReceiver()
    {
        parse_str('direction=incoming&callid=sf8425555e5d8db61dda7a7b3f1b91bdb&from=%2B46706861004&to=%2B46706861020&created=2018-07-13T13%3A57%3A23.741000', $_REQUEST);
        // Initialize the main client
        $FortySixClient = new Client('x', 'x');

        // Test phone receiver
        $FortySixClient->phone()->receiver()->handleRequest(function (ReceivedPhoneCall $receivedPhoneCall) {

            $this->assertSame([
                'direction' => $receivedPhoneCall->direction(),
                'callid' => $receivedPhoneCall->callId(),
                'from' => $receivedPhoneCall->from(),
                'to' => $receivedPhoneCall->to(),
                'created' => $receivedPhoneCall->created()
            ], $_REQUEST);

        });
    }


    public function testPhoneRouterActionHandler()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router('http://google.se');

        // register some routes
        $router
            ->register('first', function (PhoneCallAction $action) {
            })
            ->register('second', function (PhoneCallAction $action) {
            });

        $this->assertJson('{"first":"","second":""}', $router->compile());
    }

    public function testPhoneRouterActionConnect()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router('http://google.se');

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {

                return $action->connect('+46701474417');
            });

        $this->assertJson('{"testRoute":"{\"connect\":\"+46701474417\"}"}', $router->compile());
    }

    public function testPhoneRouterActionPlay()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router('http://google.se');

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {

                return $action->play('url.wav');
            });

        $this->assertJson('{"testRoute":"{\"play\":\"url.wav\"}"}', $router->compile());
    }

    public function testPhoneRouterActionIvr()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router('http://google.se');

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {

                return $action->ivr('url.wav');
            });

        $this->assertJson('{"testRoute":"{\"digits\":1,\"timeout\":30,\"repeat\":3,\"ivr\":\"url.wav\"}"}', $router->compile());
    }

    public function testPhoneRouterActionRecord()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router('http://google.se');

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {
                return $action->record('url');
            });

        $this->assertJson('{"testRoute":"{\"record\":\"url\"}"}', $router->compile());
    }

    public function testPhoneRouterActionRecordCall()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router('http://google.se');

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {
                return $action
                    ->recordCall('url')
                    ->play('somewav.mp3')
                    ->next('someOtherWay');
            });

        $this->assertJson('{"testRoute":"{\"recordcall\":\"url\",\"play\":\"somewav.mp3\",\"next\":\"http:\\\/\\\/google.se\\\/someOtherWay\"}"}', $router->compile());
    }

}

<?php


namespace Clients\PhoneCall\Services;

use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;

final class RouterTest extends TestCase
{

    public function testPhoneRouterActionHandler()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        // register some routes
        $router
            ->register('first', function (PhoneCallAction $action) {
                return $action->next('url');
            })
            ->register('second', function (PhoneCallAction $action) {
                return $action->next('url');

            });

        $this->assertJson('{"first":{"next":"http:google.seurl"},"second":{"next":"http:google.seurl"}}', $router->compile());

    }

    public function testPhoneRouterActionConnect()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {
                return $action->connect('+46701474417');
            });

        $this->assertJson('{"testRoute":{"connect":"+46701474417"}}', $router->compile());
    }

    public function testPhoneRouterActionPlay()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {

                return $action->play('url.wav');
            });

        $this->assertJson('{"testRoute":{"play":"url.wav"}}', $router->compile());
    }

    public function testPhoneRouterActionIvr()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {

                return $action->ivr('url.wav');
            });

        $this->assertJson('{"testRoute":{"digits":1,"timeout":30,"repeat":3,"ivr":"url.wav"}}', $router->compile());
    }

    public function testPhoneRouterActionRecord()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {
                return $action->record('url');
            });

        $this->assertJson('{"testRoute":{"record":"url"}}', $router->compile());
    }

    public function testPhoneRouterActionRecordCall()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {
                return $action
                    ->recordCall('url')
                    ->play('somewav.mp3')
                    ->next('someOtherWay');
            });

        $this->assertJson('{"testRoute":{"recordcall":"url","play":"somewav.mp3","next":"http:google.sesomeOtherWay"}}', $router->compile());
    }

    public function testPhoneRouterActionSerialization()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        // register some routes
        $router
            ->register('testRoute', function (PhoneCallAction $action) {
                return $action
                    ->recordCall('url')
                    ->play('somewav.mp3')
                    ->next('someOtherWay');
            });

        // compile to json
        $encodedArray = (string)$router->compile();

        // decode as array
        $decodedJson = json_decode($encodedArray, true);

        // restore in new router instance
        $router2 = $FortySixClient->phone()->router();


        $newJson = (string)$router2->compile($decodedJson);


        $this->assertSame($encodedArray, $newJson);

    }

    public function testPhoneActionInvocationVariationA()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        $router->register('test', function (PhoneCallAction $action) {

            return $action
                ->invoke(ForTestsClass::class, 'validMethod')
                ->play('test.mp3')
                ->next('anotherRoute');
        });

        $this->assertSame('{"test":{"_invoke":{"class":"Clients\\\\PhoneCall\\\\Services\\\\ForTestsClass","method":"validMethod"},"play":"test.mp3","next":"anotherRoute"}}', (string)$router->compile());
    }

    public function testPhoneActionInvocationVariationB()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        $router->register('test', function (PhoneCallAction $action) {

            return $action
                ->invoke((new ForTestsClass), 'validMethod')
                ->play('test.mp3')
                ->next('anotherRoute');
        });

        $this->assertSame('{"test":{"_invoke":{"class":"Clients\\\\PhoneCall\\\\Services\\\\ForTestsClass","method":"validMethod"},"play":"test.mp3","next":"anotherRoute"}}', (string)$router->compile());
    }

    public function testPhoneActionInvocationVariationC()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        $router->register('test', function (PhoneCallAction $action) {

            return $action
                ->invoke('Clients\\PhoneCall\\Services\\ForTestsClass@validMethod')
                ->play('test.mp3')
                ->next('anotherRoute');
        });

        $this->assertSame('{"test":{"_invoke":{"class":"Clients\\\\PhoneCall\\\\Services\\\\ForTestsClass","method":"validMethod"},"play":"test.mp3","next":"anotherRoute"}}', (string)$router->compile());
    }

    /**
     * @throws \Tarre\Php46Elks\Exceptions\InvalidActionException
     */
    public function testPhoneActionHandlerWithoutPrecompile()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        // create some routes
        $router->register('routeA', function (PhoneCallAction $action) {
            return $action
                ->play('test.mp3')
                ->invoke('Clients\\PhoneCall\\Services\\ForTestsClass@validMethod')
                ->next('routeB');
        });

        $router->register('routeB', function (PhoneCallAction $action) {
            return $action
                ->connect('+46701474417')
                ->hangUp('reject');
        });


        $routeA = $router->handle('routeA');

        $this->assertSame([
            'play' => 'test.mp3',
            'next' => 'routeB'
        ], $routeA);


        $routeB = $router->handle('routeB');

        $this->assertSame([
            'connect' => '+46701474417',
            'hangup' => 'reject'
        ], $routeB);

    }

    /**
     * @throws \Tarre\Php46Elks\Exceptions\InvalidActionException
     */
    public function testPhoneActionHandlerCompiled()
    {
        $FortySixClient = new Client('x', 'x');

        // init router
        $router = $FortySixClient->phone()->router();

        // create some routes
        $router->register('routeA', function (PhoneCallAction $action) {
            return $action
                ->play('test.mp3')
                ->invoke('Clients\\PhoneCall\\Services\\ForTestsClass@validMethod')
                ->next('routeB');
        });

        $router->register('routeB', function (PhoneCallAction $action) {
            return $action
                ->connect('+46701474417')
                ->hangUp('reject');
        });

        $router->compile();

        $routeA = $router->handle('routeA');

        $this->assertSame([
            'play' => 'test.mp3',
            'next' => 'routeB'
        ], $routeA);


        $routeB = $router->handle('routeB');

        $this->assertSame([
            'connect' => '+46701474417',
            'hangup' => 'reject'
        ], $routeB);

    }
}


class ForTestsClass
{

    public function validMethod()
    {
        $a = 1;
        $a++;
        return $a;
    }

}

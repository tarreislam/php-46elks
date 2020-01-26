<?php


namespace Clients\PhoneCall\Services;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\ReceivedPhoneCall;

final class ReceiverTest extends TestCase
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
}

<?php


namespace Clients\PhoneCall\Services;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\PhoneCallAction;

final class DispatcherTest extends TestCase
{
    /**
     * @throws \Tarre\Php46Elks\Exceptions\InvalidSenderIdException
     * @throws \Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException
     * @throws \Tarre\Php46Elks\Exceptions\NoRecipientsSetException
     * @throws \Tarre\Php46Elks\Exceptions\ActionIsAlreadySetException
     */
    public function testPhoneDispatcher()
    {
        $jsonToMock = <<<EOT
{
  "direction": "outgoing",
  "from": "+46766861004",
  "created": "2016-11-03T15:08:14.609873",
  "to": "+46766861004",
  "state": "ongoing",
  "id": "c719b1eefbf65b1f89c013e6433dbf537"
}
EOT;
        $FortySixClient = (new Client('x', 'x'))->mock();

        // add mock response
        $FortySixClient->mockHandler()->append(new Response(200, [], $jsonToMock), new Response(200, [], $jsonToMock));

        // dispatch two calls
        $recipients = ['+46766861004', '+46766861005'];

        // setup dispatcher with some default values
        $phone = $FortySixClient->phone()->from('+46766861004')->dispatcher();

        // dispatch requests
        $results = $phone
            ->voiceStart((new PhoneCallAction)->play('hello.mp3'))
            ->setRecipients($recipients);

        // validate request data
        foreach ($results->getRequests() as $request) {
            $this->assertSame([
                'from' => '+46766861004'
            ],[
                'from' => $request['from']
            ]);
        }

        // validate mocked response
        foreach ($results->send() as $result) {
            $this->assertTrue(in_array($result['to'], $recipients));
            $this->assertSame('c719b1eefbf65b1f89c013e6433dbf537', $result['id']);
        }
    }

}

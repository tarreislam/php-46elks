<?php


namespace Clients\Sms\Services;


use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\SMS\Resources\MessageResource;
use Tarre\Php46Elks\Utils\Paginator;

final class HistoryTest extends TestCase
{

    public function testHistoryGet()
    {
        $jsonToMock = <<<EOT
            {
              "data": [
                {
                  "id": "s17a6dafb12d6b1cabc053d57dac2b9d8",
                  "created": "2017-03-14T09:52:07.302000",
                  "direction": "outgoing",
                  "from": "MyService",
                  "to": "+46704508449",
                  "message": "Hello hello",
                  "status": "delivered",
                  "delivered": "2017-03-14T09:52:10Z",
                  "cost": 3500
                },
                {
                  "id": "s299b2d2a467945f59e1c9ea431eed9d8",
                  "created": "2017-03-14T08:44:34.608000",
                  "direction": "outgoing-reply",
                  "from": "+46766861069",
                  "to": "+46704508449",
                  "message": "We are open until 19:00 today. Welcome!",
                  "status": "delivered",
                  "delivered": "2017-03-14T08:44:36Z",
                  "cost": 3500
                },
                {
                  "id": "s292d2a459e967945fb1c9ea431eed9d8",
                  "created": "2017-03-14T08:44:34.135000",
                  "direction": "incoming",
                  "from": "+46704508449",
                  "to": "+46766861069",
                  "message": "Hours?",
                  "cost": 3500
                }
              ],
              "next": "2017-02-21T14:15:30.427000"
            }
EOT;

        // Initialize the main client with mock
        $FortySixClient = (new Client('x', 'x'))->mock();

        // add mock response
        $FortySixClient->mockHandler()->append(new Response(200, [], $jsonToMock));

        // Create our SMS client and set a different FROM
        $history = $FortySixClient->sms()->history();

        $paginationObject = $history->get();

        $this->assertTrue($paginationObject instanceof Paginator);

        foreach ($paginationObject->getData() as $smsMessage) {
            $this->assertTrue($smsMessage instanceof MessageResource);
        }
    }


    public function testHistoryGetById()
    {
        $jsonToMock = <<<EOT
                {
                  "id": "s17a6dafb12d6b1cabc053d57dac2b9d8",
                  "created": "2017-03-14T09:52:07.302000",
                  "direction": "outgoing",
                  "from": "MyService",
                  "to": "+46704508449",
                  "message": "Hello hello",
                  "status": "delivered",
                  "delivered": "2017-03-14T09:52:10Z",
                  "cost": 3500
                }
EOT;

        // Initialize the main client with mock
        $FortySixClient = (new Client('x', 'x'))->mock();

        // add mock response
        $FortySixClient->mockHandler()->append(new Response(200, [], $jsonToMock));

        // Create our SMS client and set a different FROM
        $history = $FortySixClient->sms()->history();

        $smsMessage = $history->getById('s17a6dafb12d6b1cabc053d57dac2b9d8');

        $this->assertTrue($smsMessage instanceof MessageResource);
    }

}

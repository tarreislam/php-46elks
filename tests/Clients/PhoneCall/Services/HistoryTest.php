<?php


namespace Clients\PhoneCall\Services;


use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\PhoneCall\Resources\CallHistory;
use Tarre\Php46Elks\Utils\Paginator;

final class HistoryTest extends TestCase
{
    public function testHistoryGet()
    {
        $jsonToMock = <<<EOT
                {
                  "data": [
                    {
                      "direction": "outgoing",
                      "from": "+46766861004",
                      "to": "+46700371815",
                      "created": "2018-02-20T11:55:27.756000",
                      "owner": "ufb9e21cb046b15feeed314e732a0e9d1",
                      "actions": [
                        {
                          "connect": "+46704508449",
                          "result": "success"
                        }
                      ],
                      "start": "2018-02-20T11:55:41.528000",
                      "state": "success",
                      "cost": 11400,
                      "timeout": 60,
                      "duration": 30,
                      "legs": [
                        {
                          "duration": 17,
                          "to": "+46704508449",
                          "state": "success",
                          "from": "+46766861004"
                        }
                      ],
                      "id": "c70b23624c022a93a87907712cf804aca"
                    }
                  ],
                  "next": "2018-02-20T10:48:03.349000"
                }
EOT;


        // Initialize the main client with mock
        $FortySixClient = (new Client('x', 'x'))->mock();

        // add mock response
        $FortySixClient->mockHandler()->append(new Response(200, [], $jsonToMock));

        // Create our SMS client and set a different FROM
        $history = $FortySixClient->phone()->history();

        $paginationObject = $history->get();

        $this->assertTrue($paginationObject instanceof Paginator);

        foreach ($paginationObject->getData() as $historyMessage) {
            $this->assertTrue($historyMessage instanceof CallHistory);
        }

    }

    public function testHistoryGetWithMissingData()
    {
        $jsonToMock = <<<EOT
                {
                  "data": [
                    {
                      "direction": "ongoing",
                      "from": "+46766861004",
                      "to": "+46700371815",
                      "start": "2018-02-20T11:55:41.528000",
                      "state": "success",
                      "id": "c70b23624c022a93a87907712cf804aca"
                    }
                  ]
                }
EOT;


        // Initialize the main client with mock
        $FortySixClient = (new Client('x', 'x'))->mock();

        // add mock response
        $FortySixClient->mockHandler()->append(new Response(200, [], $jsonToMock));

        // Create our SMS client and set a different FROM
        $history = $FortySixClient->phone()->history();

        $paginationObject = $history->get();

        $this->assertTrue($paginationObject instanceof Paginator);

        foreach ($paginationObject->getData() as $historyMessage) {
            $this->assertTrue($historyMessage instanceof CallHistory);
        }

    }

    public function testHistoryGetById()
    {
        $jsonToMock = <<<EOT
        {
              "direction": "outgoing",
              "from": "+46766861004",
              "to": "+46700371815",
              "created": "2018-02-20T11:55:27.756000",
              "owner": "ufb9e21cb046b15feeed314e732a0e9d1",
              "actions": [
                {
                  "connect": "+46704508449",
                  "result": "success"
                }
              ],
              "start": "2018-02-20T11:55:41.528000",
              "state": "success",
              "cost": 11400,
              "timeout": 60,
              "duration": 30,
              "legs": [
                {
                  "duration": 17,
                  "to": "+46704508449",
                  "state": "success",
                  "from": "+46766861004"
                }
              ],
              "id": "c70b23624c022a93a87907712cf804aca"
        }
EOT;


        // Initialize the main client with mock
        $FortySixClient = (new Client('x', 'x'))->mock();

        // add mock response
        $FortySixClient->mockHandler()->append(new Response(200, [], $jsonToMock));

        // Create our SMS client and set a different FROM
        $history = $FortySixClient->phone()->history();

        $callHistoryResource = $history->getById('c70b23624c022a93a87907712cf804aca');

        $this->assertTrue($callHistoryResource instanceof CallHistory);

    }
}

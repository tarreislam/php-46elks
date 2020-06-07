<?php


namespace Clients\Recording;


use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\Recording\Resources\Recording;
use Tarre\Php46Elks\Utils\Paginator;

class RecordingClientTest extends TestCase
{
    public function testGetAll()
    {
        $jsonToMock = <<<'EOT'
              {
                "data": [
                  {
                    "duration": 1,
                    "id": "c361e5927d2c4d7dcf71a142dc0ec6d9b-r0",
                    "created": "2016-02-28T23:54:57.075000"
                  },
                  {
                    "duration": 2,
                    "id": "c110cf16e8a0e202b7efb345ba4571d2d-r0",
                    "created": "2015-02-24T15:09:08.146000"
                  }
                  ],
                  "next": "2015-02-23T15:09:01.523100"
            }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->recording()->get();

        $this->assertInstanceOf(Paginator::class, $result);

        foreach ($result->getData() as $recording) {
            $this->assertInstanceOf(Recording::class, $recording);
        }
    }

    public function testGetById()
    {
        $jsonToMock = <<<'EOT'
           {
              "duration": 15,
              "id": "c361e5927d2c4d7dcf71a142dc0ec6d9b-r0",
              "created": "2016-02-28T23:54:57.075000"
            }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->recording()->getById('m84af96809ff8989f3668c93aaaedb69c-i0');

        $this->assertInstanceOf(Recording::class, $result);

        $this->assertSame('2016-02-28T23:54:57.075000', $result->created());
        $this->assertSame('c361e5927d2c4d7dcf71a142dc0ec6d9b-r0', $result->id());
        $this->assertSame(15, $result->duration());

    }

}

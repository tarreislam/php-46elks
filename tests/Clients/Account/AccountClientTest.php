<?php


namespace Clients\Account;


use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\Account\Resources\Account;

class AccountClientTest extends TestCase
{
    public function testGetCurrentAccount()
    {
        $jsonToMock = <<<'EOT'
            {
              "mobilenumber":"+46766861004",
              "displayname":"Your name or company",
              "id":"u5a9566c072160b318445d163949bf505",
              "currency":"SEK",
              "balance":9670500,
              "email":"help@46elks.com"
            }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->account()->me();

        $this->assertInstanceOf(Account::class, $result);

        $this->assertSame('+46766861004', $result->mobilenumber());
        $this->assertSame('Your name or company', $result->displayname());
        $this->assertSame('u5a9566c072160b318445d163949bf505', $result->id());
        $this->assertSame('SEK', $result->currency());
        $this->assertSame(9670500, $result->balance());
        $this->assertSame('help@46elks.com', $result->email());
    }

    public function testUpdateCurrentAccount()
    {
        $jsonToMock = <<<'EOT'
        {
          "mobilenumber":"+46766861647",
          "displayname":"Your name or company",
          "id":"u5a9566c072160b318445d163949bf505",
          "currency":"SEK",
          "balance":500,
          "email":"help@46elks.com",
          "creditalert":200000
        }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->account()->update(500);

        $this->assertInstanceOf(Account::class, $result);

        $this->assertSame('+46766861647', $result->mobilenumber());
        $this->assertSame('Your name or company', $result->displayname());
        $this->assertSame('u5a9566c072160b318445d163949bf505', $result->id());
        $this->assertSame('SEK', $result->currency());
        $this->assertSame(500, $result->balance());
        $this->assertSame('help@46elks.com', $result->email());
        $this->assertSame(200000, $result->creditalert());

    }

}

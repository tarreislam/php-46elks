<?php


namespace Clients\Number;

use Exception;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\Number\Resources\Number;
use Tarre\Php46Elks\Exceptions\InvalidNumberCapabilityException;
use Tarre\Php46Elks\Exceptions\InvalidNumberCategoryException;
use Tarre\Php46Elks\Exceptions\InvalidNumberOptionException;


final class NumberClientTest extends TestCase
{

    public function testConfigureNumberWithInvalidNumberCategoryException()
    {
        $client = (new Client('x', 'y'))->mock();

        try {
            $client->number()->allocate('SE', 'sms', 'wrong');
        } catch (Exception $exception) {
            $this->assertTrue($exception instanceof InvalidNumberCategoryException);
        }

    }

    public function testConfigureNumberWithInvalidNumberCapabilityException()
    {
        $client = (new Client('x', 'y'))->mock();

        try {
            $client->number()->allocate('SE', 'radio', 'fixed');
        } catch (Exception $exception) {
            $this->assertTrue($exception instanceof InvalidNumberCapabilityException);
        }

        try {
            $client->number()->allocate('SE', 'sms', 'fixed');
        } catch (Exception $exception) {
            $this->assertTrue($exception instanceof InvalidNumberCapabilityException);
        }
    }

    public function testConfigureNumberValidateNumberOptions()
    {
        $client = (new Client('x', 'y'))->mock();

        try {
            $client->number()->allocate('SE', 'voice', 'fixed', [
                'notValid' => 123
            ]);
        } catch (Exception $exception) {
            $this->assertTrue($exception instanceof InvalidNumberOptionException);
        }

        try {
            $client->number()->allocate('SE', 'voice', 'fixed', [
                'voice_start' => 'maybe'
            ]);
        } catch (Exception $exception) {
            $this->assertTrue($exception instanceof InvalidNumberOptionException);
        }

        try {
            $client->number()->allocate('SE', 'voice', 'fixed', [
                'voice_start' => 'connect: +467147'
            ]);
        } catch (Exception $exception) {
            $this->assertTrue($exception instanceof InvalidNumberOptionException);
        }
    }

    /**
     * @throws InvalidNumberCapabilityException
     * @throws InvalidNumberCategoryException
     * @throws InvalidNumberOptionException
     */
    public function testConfigureFixedSeNumberWithVoiceCapabilitiesWithSuccess()
    {

        $jsonToMock = <<<'EOT'
            {
              "id": "n57c8f48af76bf986a14f251b35389e8b",
              "active": "yes",
              "country": "se",
              "number": "+46766861001",
              "capabilities": [ "voice" ]
            }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->number()->allocate('SE', 'voice', 'fixed', [
            'voice_start' => 'http://yourapp.com/handle'
        ]);

        $this->assertInstanceOf(Number::class, $result);

        $this->assertSame("n57c8f48af76bf986a14f251b35389e8b", $result->id());
        $this->assertSame("yes", $result->active());
        $this->assertSame("+46766861001", $result->number());
        $this->assertSame('voice', $result->capabilities()[0]);
    }
}
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

    public function testAllocateNumberWithInvalidNumberCategoryException()
    {
        $client = (new Client('x', 'y'))->mock();

        try {
            $client->number()->allocate('SE', 'sms', 'wrong');
        } catch (Exception $exception) {
            $this->assertTrue($exception instanceof InvalidNumberCategoryException);
        }

    }

    public function testAllocateNumberWithInvalidNumberCapabilityException()
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

    public function testAllocateNumberValidateNumberOptions()
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
    public function testAllocateFixedSeNumberWithVoiceCapabilitiesWithSuccess()
    {

        $jsonToMock = <<<'EOT'
            {
              "id": "n57c8f48af76bf986a14f251b35389e8b",
              "active": "yes",
              "country": "se",
              "number": "+4610444555666",
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
        $this->assertSame("+4610444555666", $result->number());
        $this->assertSame('voice', $result->capabilities()[0]);
    }

    /**
     * @throws InvalidNumberCapabilityException
     * @throws InvalidNumberCategoryException
     * @throws InvalidNumberOptionException
     */
    public function testAllocateMobileNumberWithVoiceAndSmsCapabilitiesWithSuccess()
    {

        $jsonToMock = <<<'EOT'
            {
              "id": "n57c8f48af76bf986a14f251b35389e8b",
              "active": "yes",
              "country": "se",
              "number": "+46766861001",
              "capabilities": [ "voice", "sms" ]
            }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->number()->allocate('SE', [
            'voice',
            'sms'
        ], 'mobile', [
            'voice_start' => 'http://yourapp.com/handle',
            'sms_url' => '{"forward": "+46701473317"}'
        ]);

        $this->assertInstanceOf(Number::class, $result);

        $this->assertSame("n57c8f48af76bf986a14f251b35389e8b", $result->id());
        $this->assertSame("yes", $result->active());
        $this->assertSame("+46766861001", $result->number());
        $this->assertSame('voice', $result->capabilities()[0]);
        $this->assertSame('sms', $result->capabilities()[1]);
    }

    public function testDeallocateWithSuccess()
    {
        $jsonToMock = <<<'EOT'
        {
             "id": "n0ba74fef557dfcec3a96d8d4477ae634",
             "active": "no",
             "deallocated": "2018-02-22T15:23:01.611000"
        }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->number()->deallocate('n0ba74fef557dfcec3a96d8d4477ae634', 'yes');

        $this->assertInstanceOf(Number::class, $result);
        $this->assertSame('no', $result->active());
        $this->assertSame('2018-02-22T15:23:01.611000', $result->deallocated());

    }
    public function testConfigureWithSuccess()
    {
        $jsonToMock = <<<'EOT'
        {
          "id": "n57c8f48af76bf986a14f251b35389e8b",
          "active": "yes",
          "country": "se",
          "number": "+46766861001",
          "capabilities": [ "sms", "voice", "mms" ],
          "sms_url": "https://yourapp.example/elks/sms",
          "mms_url": "https://yourapp.example/elks/mms",
          "voice_start": "https://yourapp.example/elks/calls"
        }
EOT;

        $client = (new Client('x', 'y'))->mock();

        $client->mockHandler()->append(new Response(200, [], $jsonToMock));

        $result = $client->number()->configure('n57c8f48af76bf986a14f251b35389e8b', [
            'mms_url' => 'https://yourapp.example/elks/mms',
            'sms_url' => 'https://yourapp.example/elks/sms'
        ]);

        $this->assertInstanceOf(Number::class, $result);

        $this->assertSame('yes', $result->active());
        $this->assertSame('se', $result->country());
        $this->assertSame('sms', $result->capabilities()[0]);
        $this->assertSame('voice', $result->capabilities()[1]);
        $this->assertSame('mms', $result->capabilities()[2]);
        $this->assertSame('https://yourapp.example/elks/mms', $result->mms_url());
        $this->assertSame('https://yourapp.example/elks/sms', $result->sms_url());
        $this->assertSame('https://yourapp.example/elks/calls', $result->voice_start());

    }
}

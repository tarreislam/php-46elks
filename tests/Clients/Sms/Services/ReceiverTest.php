<?php


namespace Clients\Sms\Services;


use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\SMS\Resources\MessageResource;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;

final class ReceiverTest extends TestCase
{

    /**
     * @throws InvalidSenderIdException
     */
    public function testReceiveSms()
    {
        // Initialize the main client
        $FortySixClient = new Client('x', 'x');

        // Create our SMS client and set a different FROM
        $SMSClient = $FortySixClient->sms();

        $receiver = $SMSClient->receiver();

        // forge an example request and put it in $_REQUEST
        parse_str('direction=incoming&id=sf8425555e5d8db61dda7a7b3f1b91bdb&from=%2B46706861004&to=%2B46706861020&created=2018-07-13T13%3A57%3A23.741000&message=Hello%20how%20are%20you%3F', $_REQUEST);

        $receiver->handleRequest(function (MessageResource $SMS) {

            $this->assertSame($_REQUEST, [
                'direction' => $SMS->direction(),
                'id' => $SMS->id(),
                'from' => $SMS->from(),
                'to' => $SMS->to(),
                'created' => $SMS->created(),
                'message' => $SMS->message()
            ]);

        });
    }

    /**
     * @throws InvalidSenderIdException
     */
    public function testReceiveAndReply()
    {
        // Initialize the main client
        $FortySixClient = new Client('x', 'x');

        // Create our SMS client and set a different FROM
        $SMSClient = $FortySixClient->sms();

        $receiver = $SMSClient->receiver();

        // forge an example request and put it in $_REQUEST
        parse_str('direction=incoming&id=sf8425555e5d8db61dda7a7b3f1b91bdb&from=%2B46706861004&to=%2B46706861020&created=2018-07-13T13%3A57%3A23.741000&message=Hello%20how%20are%20you%3F', $_REQUEST);

        $receiver->handleRequest(function (MessageResource $SMS) {

            $response = $SMS->reply('Hello!');

            $this->assertJson($response);

            return $response;
        });
    }

    /**
     * @throws InvalidSenderIdException
     */
    public function testReceiveAndForward()
    {
        // Initialize the main client
        $FortySixClient = new Client('x', 'x');

        // Create our SMS client and set a different FROM
        $SMSClient = $FortySixClient->sms();

        $receiver = $SMSClient->receiver();

        // forge an example request and put it in $_REQUEST
        parse_str('direction=incoming&id=sf8425555e5d8db61dda7a7b3f1b91bdb&from=%2B46706861004&to=%2B46706861020&created=2018-07-13T13%3A57%3A23.741000&message=Hello%20how%20are%20you%3F', $_REQUEST);

        $receiver->handleRequest(function (MessageResource $SMS) {

            $response = $SMS->forward('+467012312321');

            $this->assertJson($response);

            return $response;
        });
    }

}

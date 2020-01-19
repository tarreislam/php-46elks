<?php

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\SMS\Resources\DeliveryReportResource;
use Tarre\Php46Elks\Clients\SMS\Resources\MessageResource;
use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;
use Tarre\Php46Elks\Exceptions\NoRecipientsSetException;
use Tarre\Php46Elks\Utils\Php46ElkPagination;

final class SmsClientTest extends TestCase
{

    /**
     * @throws InvalidE164PhoneNumberFormatException
     * @throws InvalidSenderIdException
     * @throws NoRecipientsSetException
     */
    public function testSendSms()
    {
        $expectedRecipients = ['+46701472117', '+46701474415'];

        // Initialize the main client
        $FortySixClient = new Client('x', 'x');

        // Create our SMS client
        $SMSClient = $FortySixClient->sms()->from('Php46Elks');

        // Create new message
        $dispatcher = $SMSClient->dispatcher();

        // add some recipients
        $dispatcher->recipient('+46701472117');
        $dispatcher->recipient('+46701474415');

        // set some lines
        $dispatcher->setLines([
            'Hello'
        ]);

        foreach ($dispatcher->getRequests() as $key => $request) {
            $this->assertSame([
                'to' => $expectedRecipients[$key],
                'from' => 'Php46Elks',
                'message' => 'Hello'
            ], $request);
        }

    }


    /**
     * @throws InvalidE164PhoneNumberFormatException
     * @throws InvalidSenderIdException
     * @throws NoRecipientsSetException
     */
    public function testSendSmsFromSetFromClient()
    {
        $expectedRecipients = ['+46701472117'];

        // Initialize the main client
        $FortySixClient = new Client('x', 'x');

        // Create our SMS client and set a different FROM
        $SMSClient = $FortySixClient->sms()->from('NewName');

        // Create new message
        $dispatcher = $SMSClient->dispatcher();

        // add some recipients
        $dispatcher->recipient('+46701472117');

        // set some lines
        $dispatcher->setLines([
            'Hello'
        ]);

        foreach ($dispatcher->getRequests() as $key => $request) {
            $this->assertSame([
                'to' => $expectedRecipients[$key],
                'from' => 'NewName',
                'message' => 'Hello'
            ], $request);
        }
    }

    /**
     * @throws InvalidE164PhoneNumberFormatException
     * @throws InvalidSenderIdException
     * @throws NoRecipientsSetException
     */
    public function testSendSmsFromSetFromMessage()
    {

        $expectedRecipients = ['+46701472117'];

        // Initialize the main client
        $FortySixClient = new Client('x', 'x');

        // Create our SMS client and set a different FROM
        $SMSClient = $FortySixClient->sms()->from('NewName');

        // Create new message
        $dispatcher = $SMSClient->dispatcher()->from('NewerName');

        // add some recipients
        $dispatcher->recipient('+46701472117');

        // set some lines
        $dispatcher->setLines([
            'Hello'
        ]);

        foreach ($dispatcher->getRequests() as $key => $request) {
            $this->assertSame([
                'to' => $expectedRecipients[$key],
                'from' => 'NewerName',
                'message' => 'Hello'
            ], $request);
        }
    }

    /**
     * @throws InvalidE164PhoneNumberFormatException
     * @throws InvalidSenderIdException
     * @throws NoRecipientsSetException
     */
    public function testSendSmsWithDifferentMethods()
    {
        $expectedRecipients = ['+46701472117'];

        // Initialize the main client
        $FortySixClient = new Client('x', 'x');

        // Create our SMS client and set a different FROM
        $SMSClient = $FortySixClient->sms()->from('Php46Elks');

        // Create new message
        $dispatcher = $SMSClient->dispatcher();

        // add some recipients
        $dispatcher->setRecipients($expectedRecipients);

        // set some lines
        $dispatcher->content('Hello');


        foreach ($dispatcher->getRequests() as $key => $request) {
            $this->assertSame([
                'to' => $expectedRecipients[$key],
                'from' => 'Php46Elks',
                'message' => 'Hello'
            ], $request);
        }
    }

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

    /**
     * @throws InvalidSenderIdException
     */
    public function testDLR()
    {
        // Initialize the main client
        $FortySixClient = new Client('x', 'x');

        // Create our SMS client and set a different FROM
        $SMSClient = $FortySixClient->sms();

        $receiver = $SMSClient->dlr();

        // forge an example request and put it in $_REQUEST
        parse_str('id=123&status=failed&delivered=2018-07-13T13%3A57%3A23.741000', $_REQUEST);

        $receiver->handleRequest(function (DeliveryReportResource $SMS) {

            $this->assertSame($_REQUEST, [
                'id' => $SMS->id(),
                'status' => $SMS->status(),
                'delivered' => $SMS->delivered()
            ]);

        });
    }

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

        $this->assertTrue($paginationObject instanceof Php46ElkPagination);

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

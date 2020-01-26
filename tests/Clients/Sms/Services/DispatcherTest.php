<?php


namespace Clients\Sms\Services;

use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;
use Tarre\Php46Elks\Exceptions\NoRecipientsSetException;


final class DispatcherTest extends TestCase
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

        // set some lines and a webhook
        $dispatcher->content('Hello')
            ->whenDelivered('https://myapp');


        foreach ($dispatcher->getRequests() as $key => $request) {
            $this->assertSame([
                'to' => $expectedRecipients[$key],
                'from' => 'Php46Elks',
                'message' => 'Hello',
                'whendelivered' => 'https://myapp'
            ], $request);
        }
    }


}

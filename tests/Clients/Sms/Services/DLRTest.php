<?php


namespace Clients\Sms\Services;


use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Client;
use Tarre\Php46Elks\Clients\SMS\Resources\DeliveryReport;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;

final class DLRTest extends TestCase
{

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

        $receiver->handleRequest(function (DeliveryReport $SMS) {

            $this->assertSame($_REQUEST, [
                'id' => $SMS->id(),
                'status' => $SMS->status(),
                'delivered' => $SMS->delivered()
            ]);

        });
    }
}

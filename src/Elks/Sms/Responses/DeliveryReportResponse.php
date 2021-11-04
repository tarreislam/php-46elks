<?php

namespace Tarre\Php46Elks\Elks\Sms\Responses;

use Tarre\Php46Elks\ConstructSetterFactory;
use Tarre\Php46Elks\Elks\Sms\traits\DeliveredTrait;
use Tarre\Php46Elks\Elks\Sms\traits\IdTrait;
use Tarre\Php46Elks\Elks\Sms\traits\StatusTrait;

class DeliveryReportResponse extends ConstructSetterFactory
{
    use IdTrait, StatusTrait, DeliveredTrait;

}

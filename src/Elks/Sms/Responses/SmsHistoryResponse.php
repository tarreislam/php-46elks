<?php

namespace Tarre\Php46Elks\Elks\Sms\Responses;

use Tarre\Php46Elks\ConstructSetterFactory;
use Tarre\Php46Elks\Elks\Sms\traits\CostTrait;
use Tarre\Php46Elks\Elks\Sms\traits\CreatedTrait;
use Tarre\Php46Elks\Elks\Sms\traits\DeliveredTrait;
use Tarre\Php46Elks\Elks\Sms\traits\DirectionTrait;
use Tarre\Php46Elks\Elks\Sms\traits\FromTrait;
use Tarre\Php46Elks\Elks\Sms\traits\IdTrait;
use Tarre\Php46Elks\Elks\Sms\traits\MessageTrait;
use Tarre\Php46Elks\Elks\Sms\traits\StatusTrait;
use Tarre\Php46Elks\Elks\Sms\traits\ToTrait;

class SmsHistoryResponse extends ConstructSetterFactory
{
    use IdTrait, DirectionTrait, FromTrait, ToTrait, CreatedTrait, DeliveredTrait, MessageTrait, StatusTrait, CostTrait;
}

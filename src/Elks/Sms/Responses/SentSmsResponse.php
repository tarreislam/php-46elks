<?php

namespace Tarre\Php46Elks\Elks\Sms\Responses;

use Tarre\Php46Elks\ConstructSetterFactory;
use Tarre\Php46Elks\Elks\Sms\traits\CostTrait;
use Tarre\Php46Elks\Elks\Sms\traits\CreatedTrait;
use Tarre\Php46Elks\Elks\Sms\traits\DeliveredTrait;
use Tarre\Php46Elks\Elks\Sms\traits\DirectionTrait;
use Tarre\Php46Elks\Elks\Sms\traits\DontLogTrait;
use Tarre\Php46Elks\Elks\Sms\traits\EstimatedCostTrait;
use Tarre\Php46Elks\Elks\Sms\traits\FromTrait;
use Tarre\Php46Elks\Elks\Sms\traits\IdTrait;
use Tarre\Php46Elks\Elks\Sms\traits\MessageTrait;
use Tarre\Php46Elks\Elks\Sms\traits\PartsTrait;
use Tarre\Php46Elks\Elks\Sms\traits\StatusTrait;
use Tarre\Php46Elks\Elks\Sms\traits\ToTrait;

class SentSmsResponse extends ConstructSetterFactory
{
    use IdTrait, StatusTrait, FromTrait, ToTrait, MessageTrait, CreatedTrait, DeliveredTrait, CostTrait, DirectionTrait, DontLogTrait, EstimatedCostTrait, PartsTrait;
}

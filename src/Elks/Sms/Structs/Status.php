<?php

namespace Tarre\Php46Elks\Elks\Sms\Structs;

interface Status
{
    const CREATED = 'created';
    const SENT = 'sent';
    const FAILED = 'failed';
    const DELIVERED = 'delivered';
}

<?php


namespace Tarre\Php46Elks\Clients\PhoneCall\Services;


use Tarre\Php46Elks\Interfaces\DataResourceInterface;
use Tarre\Php46Elks\Traits\DataResourceFilterTrait;
use Tarre\Php46Elks\Utils\Paginator;

class PhoneCallHistoryService implements DataResourceInterface
{
    use DataResourceFilterTrait;

    /**
     * @inheritDoc
     */
    public function get(): Paginator
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id)
    {
        // TODO: Implement getById() method.
    }
}

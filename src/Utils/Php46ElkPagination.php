<?php


namespace Tarre\Php46Elks\Utils;


class Php46ElkPagination
{
    protected $items;

    /**
     * Php46ElkPagination constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->items = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->items['data'];
    }

    /**
     * @return bool
     */
    public function getNext()
    {
        return $this->items['next'] || null;
    }

}

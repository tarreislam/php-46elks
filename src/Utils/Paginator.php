<?php


namespace Tarre\Php46Elks\Utils;


class Paginator
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
     * @return string|null
     */
    public function getNext()
    {
        return isset($this->items['next']) ? $this->items['next'] : null;
    }

}

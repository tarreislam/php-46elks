<?php


namespace Tarre\Php46Elks\Interfaces;


use Tarre\Php46Elks\Utils\Paginator;

interface DataResourceInterface
{
    /**
     * @return Paginator
     */
    public function get(): Paginator;

    /**
     * @param string $id
     * @return mixed
     */
    public function getById(string $id);

}

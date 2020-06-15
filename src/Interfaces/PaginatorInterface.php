<?php

namespace Tarre\Php46Elks\Interfaces;

use Tarre\Php46Elks\Utils\Paginator;

interface PaginatorInterface
{
    /**
     * Retrieve resources before this date.
     *
     * @param $start
     * @return $this
     */
    public function start($start);

    /**
     * Retrieve resources after this date.
     *
     * @param $end
     * @return $this
     */
    public function end($end);

    /**
     * Limit the number of results on each page.
     *
     * @param $limit
     * @return $this
     */
    public function limit($limit);

    /**
     * Get resource
     *
     * @return Paginator
     */
    public function get(): Paginator;

    /**
     * Get resourde by id
     *
     * @param string $id
     * @return mixed
     */
    public function getById(string $id);

}

<?php

namespace Tarre\Php46Elks\Interfaces;

use Tarre\Php46Elks\Utils\FileResource;

interface HasFileInterface
{

    /**
     * Return a FileResource class
     *
     * @return FileResource
     */
    public function withFile(): FileResource;

}

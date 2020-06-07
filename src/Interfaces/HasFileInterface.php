<?php


namespace Tarre\Php46Elks\Interfaces;


use Tarre\Php46Elks\Utils\FileResource;

interface HasFileInterface
{

    public function withFile(): FileResource;

}

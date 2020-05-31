<?php


namespace Tarre\Php46Elks\Interfaces;


interface ModelResource
{
    public function exists();

    public function save();

    public function delete();
}
<?php namespace NSRosenqvist\Soma\Cache\Facades;

class Cache extends \Soma\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cache';
    }
}

<?php

if (! function_exists('get_cache')) {
    function get_cache($key, $callable, $beta = 1.0)
    {
        return app('cache')->get($key, $callable, $beta);
    }
}

if (! function_exists('delete_cache')) {
    function delete_cache($key)
    {
        return app('cache')->delete($key);        
    }
}

if (! function_exists('get_cache_item')) {
    function get_cache_item($key)
    {
        return app('cache')->getItem($key);        
    }
}

if (! function_exists('save_cache_item')) {
    function save_cache_item($item)
    {
        return app('cache')->save($item);        
    }
}

if (! function_exists('delete_cache_item')) {
    function delete_cache_item($key)
    {
        return app('cache')->deleteItem($key);        
    }
}
<?php 
namespace Universal\Session\SaveHandler;

class MemcacheSaveHandler 
{
    public function __construct($savePath = null )
    {
        ini_set('session.save_handler', 'memcache');

        if( ! $savePath )
            $savePath = 'tcp://127.0.0.1:11211?persistent=1';

        // $session_save_path = "tcp://$host:$port?persistent=1&weight=2&timeout=2&retry_interval=10,  ,tcp://$host:$port  ";
        ini_set('session.save_path', is_array($savePath) ? join(',', $savePath) : $savePath );
    }
}


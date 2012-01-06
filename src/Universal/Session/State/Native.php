<?php 
namespace Universal\Session\State;

class Native 
{

    function __construct()
    {
        if( ! isset($_SESSION ))
            session_start();
    }

    function getSid()
    {
        return session_id();
    }

    function generateSid()
    {
        return session_regenerate_id();
    }


}


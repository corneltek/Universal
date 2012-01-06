<?php 
namespace Universal\Session;


/**
    $session = new Session(array(
            'storage' => ... ));
    $session->setStorage( new FileStorage( ) );
    $session->setStorage( new MongoStorage(  ) );
    $session->setStorage( new MySQLStorage(  ) );

    $session->get('blah');
    $session->set('blah');
 */
class SessionManager
{
    private $_storage;


    public $sessionId;

    function __construct() 
    {
        // try to get session id from cookie, or generate one.
        // like: 
        //    Cookie:PHPSESSID=64jl9oov6a89ktqlv2d5j6hlq6
        $this->sessionId = 
            isset( $_COOKIE['session'] ) ?: $this->generateId();
    }

    function generateId()
    {
        return sha1( microtime() );
    }





}

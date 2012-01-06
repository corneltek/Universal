<?php 
namespace Universal\Session\State;

class Cookie
{
    public $sessionKey;

    /**
     * secret string to sign cookie data
     */
    private $secret;

    function __construct($options = array())
    {
        $this->sessionKey = isset($options['cookie_id']) ? $options['cookie_id'] : 'session';
        $this->secret     = isset($options['secret']) ? $options['secret'] : md5(microtime());
    }

    public function getSid()
    {
        return isset( $_COOKIE[$this->sessionKey] ) ?
                    $_COOKIE[$this->sessionKey] : $this->generateSid();
    }


    /**
     * sid generator
     */
    public function generateSid()
    {
        return sha1( rand() . microtime() );
    }


    /**
     * validate sid string
     */
    public function validateSid($sid)
    {
        return preg_match( '/\A[0-9a-f]{40}\Z/' , $sid );
    }


    /**
     * update state, cookie expiry time ... etc
     */
    function update()
    {

    }

    function _setCookie()
    {

    }

}

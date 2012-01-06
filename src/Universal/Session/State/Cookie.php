<?php 
namespace Universal\Session\State;

class Cookie
{
    public $cookieParams = array();
    public $sessionKey;

    /**
     * secret string to sign cookie data
     */
    private $secret;

    function __construct($options = array())
    {
        $this->sessionKey = isset($options['cookie_id']) ? $options['cookie_id'] : 'session';
        $this->secret     = isset($options['secret']) ? $options['secret'] : md5(microtime());

        $sid = $this->getSid();
        if( ! $sid )
            $sid = $this->generateSid();

    }

    public function getSid()
    {
        return @$_COOKIE[$this->sessionKey];
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
     * sign data with sha1 and secret key
     */
    public function sign($data)
    {
        return hash_hmac('sha1', $data , $this->secret );
        // return hash_hmac('sha256', $data , $this->secret );
    }

    /**
     * update state, cookie expiry time ... etc
     */
    function update($sid,$data,$params = array() )
    {

    }

    function _setCookie()
    {

    }

}

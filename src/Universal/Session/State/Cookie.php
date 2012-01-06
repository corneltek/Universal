<?php 
namespace Universal\Session\State;

class Cookie
{
    public $cookieParams;
    public $sessionKey;

    function __construct($options = array())
    {
        $this->sessionKey = isset($options['cookie_id']) ? $options['cookie_id'] : 'session';
        // $this->secret     = isset($options['secret']) ? $options['secret'] : md5(microtime());

        /* default cookie param */
        $this->cookieParams = isset($options['cookie_params']) ? $options['cookie_params'] 
            : array(
                'path'     => '/',
                'expire'   => 0,
                'domain'   => null, //
                'secure'   => null, // false,
                'httponly' => null, // false,
            );
    }

    public function getSid()
    {
        return isset($_COOKIE[$this->sessionKey]) ? $_COOKIE[$this->sessionKey] : $this->generateSid();
    }


    /**
     * write cookie
     */
    public function write($sid)
    {
        // bool setcookie ( string $name [, string $value [, int $expire = 0 [, 
        //    string $path [, string $domain [, bool $secure = false [, bool 
        //    $httponly = false ]]]]]] )
        setcookie( $this->sessionKey , $sid , 
            $this->cookieParams['expire'],
            $this->cookieParams['path'],
            $this->cookieParams['domain'],
            @$this->cookieParams['secure'],
            @$this->cookieParams['httponly']
        );
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
    /*
    public function sign($data)
    {
        return hash_hmac('sha1', $data , $this->secret );
        // return hash_hmac('sha256', $data , $this->secret );
    }
    */



    /**
     * update state, cookie expiry time ... etc
     */
    function update($sid,$data,$params = array() )
    {

    }

}

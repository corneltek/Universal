<?php 
namespace Universal\Session\State;

class CookieState
{
    public $cookieParams;
    public $sessionKey;
    public $sessionId;

    function __construct($options = array())
    {
        $this->sessionKey = isset($options['cookie_id']) ? $options['cookie_id'] : 'session';
        // $this->secret     = isset($options['secret']) ? $options['secret'] : md5(microtime());


        /* default cookie param */
        $cookieParams = array(
            'path'     => '/',
            'expire'   => 0,
            'domain'   => null, //
            'secure'   => false, // false,
            'httponly' => false, // false,
        );

        $this->cookieParams = isset( $options['cookie_params'] ) ? 
            array_merge( $cookieParams , (array) $options['cookie_params'] ) :
            $cookieParams;

        $this->sessionId = $this->getSid();
        if( ! $this->validateSid($this->sessionId) )
            throw new Exception( "Invalid Session Id" );

        if( ! isset($_SERVER['argv']) ) {
            $this->write( $this->sessionId );
        }
    }

    public function getSid()
    {
        return isset($_COOKIE[$this->sessionKey]) ? $_COOKIE[$this->sessionKey] : $this->generateSid();
    }

    /**
     * set default cookie params
     */
    public function setCookieParams(array $config)
    {
        $this->cookieParams = array_merge( $this->cookieParams , $config );
    }


    /**
     * let current cookie be expired.
     */
    public function setCookieExpire()
    {
        $this->setSessionCookie(array( 'expire' => time() ));
    }

    public function setSessionCookie($config = null)
    {
        if( $config )
            $config = array_merge( $this->cookieParams , $config );

        // bool setcookie ( string $name [, string $value [, int $expire = 0 [, 
        //    string $path [, string $domain [, bool $secure = false [, bool 
        //    $httponly = false ]]]]]] )
        setcookie( $this->sessionKey , $this->sessionId, 
            @$config['expire'],
            @$config['path'],
            @$config['domain'],
            @$config['secure'],
            @$config['httponly']
        );
    }


    /**
     * write cookie
     */
    public function write()
    {
        $this->setSessionCookie();
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

}

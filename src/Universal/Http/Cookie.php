<?php
namespace Universal\Http;

/**
 *    $cookie = new Cookie
 *    $cookie->path = '/path';
 *    $cookie->set( 'name' , 123 );
 */
class Cookie
{
    public $domain;

    public $expired;

    public $secure;

    public $path;

    public function path($path) { $this->path = $path; }

    public function secure($secure) { $this->secure = $secure; }

    public function expired($expired) { $this->expired = $expired; }

    public function domain($domain) { $this->domain = $domain; }

    function set($name,$value)
    {
        setcookie( $name, $value , $this->expired , $this->path , $this->domain, $this->secure );
    }
}





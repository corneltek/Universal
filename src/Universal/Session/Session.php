<?php 

namespace Universal\Session;

class Session 
        implements ArrayAccess
{
    private $state;
    private $storage;

    public function __construct( $options = array() )
    {
        $this->state = isset($options['state']) ? $options['state'] : new State\CookieState; // or built-in

        $this->storage = isset($options['storage']) ? $options['storage'] : 
                    new SessionStorage\NativeStorage; // Use php native session storage by default

        // load session data by session id.
        $this->storage->load( $this->state->getSid() );
    }

    public function __set($name,$value)
    {
        return $this->storage->set( $name, $value );
    }

    public function __get($name)
    {
        return $this->storage->get( $name );
    }

    public function __isset($name)
    {
        return $this->storage->has( $name );
    }

    public function offsetSet($name,$value) { }
    public function offsetGet($name,$value) { }
    public function offsetExists($name,$value) { }
    public function offsetUnset($name) {  }
}

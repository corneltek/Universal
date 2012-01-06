<?php 
namespace Universal\Session;
use ArrayAccess;

class Session 
    implements ArrayAccess
{
    private $state;
    private $storage;


    /**
     * contruct
     *
     * @param array|Universal\Container\ObjectContainer $options default option
     *
     * options:
     *   can be ObjectContainer or array.
     *
     *   array:
     *      state: State object.
     *      storage: Storage object.
     */
    public function __construct( $options = array() )
    {
        if( is_array( $options ) ) 
        {
            $this->state = isset($options['state']) 
                ? $options['state'] 
                : new State\NativeState;
                // : new State\Cookie; // or built-in

            $this->storage = isset($options['storage']) 
                ? $options['storage'] 
                : new Storage\NativeStorage; // Use php native session storage by default
        }
        elseif ( is_a( '\Universal\Container\ObjectContainer', $options ) ) 
        {
            $this->state   = $options->state   ?: new State\NativeState;
            $this->storage = $options->storage ?: new Storage\NativeStorage;
        }

        // load session data by session id.
        $this->storage->load( $this->state->getSid() );
    }

    public function getState()
    {
        return $this->state;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function set($name,$value)
    {
        return $this->storage->set( $name, $value );
    }

    public function get($name)
    {
        return $this->storage->get( $name );
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

    public function offsetSet($name,$value) 
    {
        return $this->storage->set( $name, $value );
    }
    public function offsetGet($name) 
    {
        return $this->storage->get( $name );
    }

    public function offsetExists($name) 
    {
        return $this->storage->has( $name );
    }

    public function offsetUnset($name) 
    {
        return $this->storage->delete( $name );
    }

}

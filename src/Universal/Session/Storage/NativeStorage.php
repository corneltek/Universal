<?php 
namespace Universal\Session\Storage;

/**
 * Native php session storage
 *
 */
class NativeStorage
    implements StorageInterface
{

    public function get($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name]: null;
    }

    public function set($name,$value) 
    {
        $_SESSION[$name] = $value;
    }

    public function has($name)
    {
        return isset( $_SESSION[$name] );
    }

    public function delete($name)
    {
        unset( $_SESSION[$name] );
    }

    public function load($sessionId)
    {

    }

    public function sync()
    {
        // we don't really need this.
        // session_write_close();
    }

    public function destroy()
    {
        session_destroy();
    }


    public function setGcProbability($d)
    {
        ini_set('session.gc_probability', $d);
    }

    public function setGcDivisor($d)
    {
        ini_set('session.gc_divisor', $d);
    }

}

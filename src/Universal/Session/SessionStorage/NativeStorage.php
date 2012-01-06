<?php 
namespace Universal\Session\SessionStorage;

class NativeStorage
    implements StorageInterface
{

    public function get($name)
    {
        return $_SESSION[$name];
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

    public function destroy()
    {
        session_destroy();
    }
}

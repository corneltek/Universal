<?php
namespace Universal\ClassLoader;
use Exception;

if( class_exists('\Universal\ClassLoader\ApcClassLoader') )
    return;

class ApcClassLoader extends SplClassLoader
{
    private $_apcNamespace = 'apc';

    public function __construct($namespaces = null)
    {
        parent::__construct( $namespaces );
        if( ! extension_loaded('apc') )
            throw new Exception('apc extension is not loaded.');
    }

    public function setApcNamespace($ns)
    {
        $this->_apcNamespace = $ns;
    }

    public function loadClass($class)
    {
        if( ($file = apc_fetch($this->_apcNamespace . '::' . $class) ) !== false ) {
            require $file;
            return true;
        }

        if ($file = $this->findClassFile($class)) {
            apc_store( $this->_apcNamespace . '::' . $class , $file );
            require $file;
            return true;
        }
        return false;
    }
}

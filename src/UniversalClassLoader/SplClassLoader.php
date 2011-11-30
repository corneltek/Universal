<?php
/*
 * This file is part of the UniversalClassLoader package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 *      $loader = new \UniversalClassLoader\SplClassLoader( array(  
 *               'Onion' => 'path/to/Onion',
 *               'CLIFramework' => 'path/to/CLIFramework',
 *           ));
 *      $loader->register();
 *
 */
namespace UniversalClassLoader;

class SplClassLoader
{
    public $prefixes;
    public $systemWidePaths;

    public function __construct( $prefixes = array() )
    {
        $this->prefixes = $prefixes;
    }

    public function includeSystemWide()
    {
        $this->systemWidePaths = explode(':',get_include_path());
    }

    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     * @return void
     */
    public function loadClass($class)
    {
        $class = ltrim($class,'\\');
        if( ($r = strpos($class,'\\')) !== false ) {
            $p = substr($class,0,($r-1));
            if( isset($this->prefixes[ $p ] ) ) {
                $path = $this->prefixes[ $p ];
                $filename = $path . str_replace(array('\\'), DIRECTORY_SEPARATOR, $class ) . '.php';
            }
        }

        if (null === $this->_namespace || 
                $this->_namespace.$this->_namespaceSeparator === substr($className, 0, strlen($this->_namespace.$this->_namespaceSeparator))) {
            $fileName = '';
            $namespace = '';
            if (false !== ($lastNsPos = strripos($className, $this->_namespaceSeparator))) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace($this->_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $this->_fileExtension;
            $target = ($this->_includePath !== null 
                        ?  $this->_includePath . DIRECTORY_SEPARATOR 
                        : '') 
                        . $fileName;
            require $target;
        }
    }
}

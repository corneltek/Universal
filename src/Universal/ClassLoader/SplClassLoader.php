<?php
/**
 * This file is part of the Universal package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Load Class with namespaces
 *
 *      $loader = new \UniversalClassLoader\SplClassLoader( array(  
 *               'Vendor\Onion' => 'path/to/Onion',
 *               'Vendor\CLIFramework' => 'path/to/CLIFramework',
 *      ));
 *
 *      $loader->addNamespace(array( 
 *          'NS' => 
 *      ));
 *
 *      $loader->useIncludePath();
 *      $loader->register();
 *
 */
namespace Universal\ClassLoader;
use Exception;

class SplClassLoader
{
    public $namespaces = array();
    public $prefixes = array();
    public $fallbacks = array();

    public $useIncludePath;
    public $mode;

    public function __construct($namespaces = null)
    {
        if( $namespaces )
            $this->addNamespace( $namespaces );
    }

    public function addNamespace($ns = array())
    {
        if( is_array($ns) ) {
            foreach( $ns as $n => $dirs )
                $this->namespaces[ $n ] = (array) $dirs;
            return;
        } 
        else {
            $args = func_get_args();
            if( count( $args ) == 2 ) {
                $this->namespaces[ $args[0] ] = (array) $args[1];
                return;
            }
        }
        throw new Exception;
    }

    public function addPrefix($ps = array())
    {
        foreach ($ps as $prefix => $dirs) {
            $this->prefixes[$prefix] = (array) $dirs;
        }
    }

    public function addFallback($path)
    {
        $this->fallbacks[] = $path;
    }

    public function useIncludePath($bool)
    {
        $this->useIncludePath = $bool;
    }

    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    public function findClassFile($fullclass)
    {
        $fullclass = ltrim($fullclass,'\\');
        # echo "Fullclass: " . $fullclass . "\n";

        $subpath = null;
        if( ($r = strrpos($fullclass,'\\')) !== false ) {
            $namespace = substr($fullclass,0,$r);
            $classname = substr($fullclass,$r + 1);
            $subpath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace )
                    . DIRECTORY_SEPARATOR . str_replace( '_' , DIRECTORY_SEPARATOR , $classname ) 
                    . '.php';
            foreach( $this->namespaces as $ns => $dirs ) {

                # echo "namespace: $ns in $namespace\n";
                if( strpos($namespace,$ns) !== 0 )
                    continue;

                foreach( $dirs as $d ) {
                    $path = $d . DIRECTORY_SEPARATOR . $subpath;
                    if( file_exists($path) )
                        return $path;
                }
            }
        }
        else {
            // use prefix to load class (pear style), convert _ to DIRECTORY_SEPARATOR.
            $subpath = str_replace('_', DIRECTORY_SEPARATOR, $fullclass).'.php';
            foreach ($this->prefixes as $p => $dirs) {
                if (strpos($fullclass, $p) !== 0)
                    continue;
                foreach ($dirs as $dir) {
                    $file = $dir.DIRECTORY_SEPARATOR.$subpath;
                    if (file_exists($file))
                        return $file;
                }
            }
        }

        foreach( $this->fallbacks as $fallback ) {
            $file = $fallback . DIRECTORY_SEPARATOR . $subpath;
            if( file_exists($file) )
                require $file;
        }

        if ($this->useIncludePath && $file = stream_resolve_include_path($subpath))
            return $file;
    }

    public function loadClass($class)
    {
        if ($file = $this->findClassFile($class)) {
            # echo "File: $file.\n";
            require $file;
        }
    }
}

<?php
namespace Universal\ClassLoader;

class Psr0ClassLoader implements ClassLoader
{

    /**
     * namespace mapping
     *
     * @var array
     */
    protected $namespaces = array();

    public function addNamespace($ns, $dirs)
    {
        $this->namespaces[$ns] = (array) $dirs;
    }


    /**
     * find class file path
     *
     * @param string $fullclass
     */
    public function resolveClass($fullclass)
    {
        if (($r = strrpos($fullclass,'\\')) !== false) {
            $namespace = substr($fullclass,0,$r);
            $classname = substr($fullclass,$r + 1);
            $subpath = strtr($fullclass, '\\', DIRECTORY_SEPARATOR) . '.php';
            foreach ($this->namespaces as $ns => $dirs) {
                if (strpos($namespace,$ns) === 0) {
                    foreach ($dirs as $dir) {
                        $path = $dir . DIRECTORY_SEPARATOR . $subpath;
                        if (file_exists($path)) {
                            return $path;
                        }
                    }
                }
            }
        }
    }

    /**
     * register to spl_autoload_register
     *
     * @param boolean $prepend
     */
    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    /**
     * unregister the spl autoloader
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }
}





<?php
namespace Universal\ClassLoader;

class Psr4ClassLoader
{
    protected $prefixes = array();

    public function __construct(array $prefixes = array()) 
    {
        $this->prefixes = $prefixes;
    }

    public function addPrefix($prefix, $dir)
    {
        $this->prefixes[] = [$prefix, $dir];
    }


    /**
     * find class file path
     *
     * @param string $fullclass
     */
    public function resolveClass($fullclass)
    {
        # echo "Fullclass: " . $fullclass . "\n";
        foreach ($this->prefixes as $prefixMap) {
            list($prefix, $dir) = $prefixMap;
            if (strpos($fullclass, $prefix) === 0) {
                $len = strlen($prefix);
                $classSuffix = substr($fullclass, $len);
                $subpath = str_replace('\\', DIRECTORY_SEPARATOR, $classSuffix) . '.php';
                $classPath = $dir . $subpath;
                if (file_exists($classPath)) {
                    return $classPath;
                }
            }
        }
    }

    public function loadClass($class)
    {
        if ($file = $this->resolveClass($class)) {
            require $file;
            return true;
        }
        return false;
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

    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

}


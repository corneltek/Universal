<?php
namespace Universal\ClassLoader;

interface ClassLoader { 

    public function register($prepend = false);

    public function unregister();

    public function resolveClass($fullclass);

    public function loadClass($class);

}



<?php
define('basedir',dirname(__DIR__));
require basedir . '/src/Universal/ClassLoader/BasePathClassLoader.php';
use Universal\ClassLoader\BasePathClassLoader;
$classloader = new BasePathClassLoader(array(basedir . '/src'));
$classloader->register();

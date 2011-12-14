<?php
require 'tests/helpers.php';
require 'src/Universal/ClassLoader/SplClassLoader.php';
$loader = new \Universal\ClassLoader\SplClassLoader( array(  
    'UniversalClassLoader' => 'src'
));
$loader->register();

<?php
require 'tests/helpers.php';
require 'src/UniversalClassLoader/SplClassLoader.php';
$loader = new \UniversalClassLoader\SplClassLoader( array(  
    'UniversalClassLoader' => 'src'
));
$loader->register();

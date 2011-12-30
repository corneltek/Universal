<?php 

namespace Universal\ClassLoader;
use PHPUnit_Framework_TestCase;
use Exception;

class BasePathClassLoaderTest extends PHPUnit_Framework_TestCase
{
    function testFunc()
    {
        $loader = new BasePathClassLoader( array( 
            'vendor/pear', 'external_vendor/src'
        ));
        $loader->register();
        ok( $loader );
    }
}



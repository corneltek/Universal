<?php
/*
 * This file is part of the UniversalClassLoader package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

class SplClassLoaderTest extends PHPUnit_Framework_TestCase 
{
    function test()
    {
        $loader = new \Universal\ClassLoader\SplClassLoader;
        ok( $loader );
        $loader->addNamespace('Foo', 'tests' . DIRECTORY_SEPARATOR . 'lib');
        $loader->register();

        $foo = new \Foo\Foo;
        ok( $foo );

        $bar = new \Foo\Bar;
        ok( $bar );

        $loader->unregister();
    }

    function testReloadGuard()
    {
        require 'src/Universal/ClassLoader/SplClassLoader.php';
        require 'src/Universal/ClassLoader/SplClassLoader.php';
    }
}



<?php
/*
 * This file is part of the {{ }} package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Universal\Container;
use PHPUnit_Framework_TestCase;
class ObjectContainerTest extends PHPUnit_Framework_TestCase 
{
    public function testSingletonBuilder() 
    {
        $container = new ObjectContainer;
        $container->std = function() { 
            return new \stdClass;
        };
        ok( $container->std );
        is( $container->std , $container->std );
    }

    public function testFactoryBuilder()
    {
        $container = new ObjectContainer;
        $container->registerFactory('std',function($args) { 
            return $args;
        });

        $a = $container->getInstance('std',array(1));
        ok($a);

        $b = $container->getInstance('std',array(2));
        ok($b);

        is(1,$a);
        is(2,$b);
    }
}


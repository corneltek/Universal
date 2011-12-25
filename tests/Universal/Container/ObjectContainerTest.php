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
    function test() 
    {
        $container = new ObjectContainer;
        $container->std = function() { 
            return new \stdClass;
        };
        ok( $container->std );
    }
}


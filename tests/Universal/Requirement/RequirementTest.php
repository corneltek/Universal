<?php 




namespace Universal\Requirement;
use PHPUnit_Framework_TestCase;
use Exception;

class RequirementTest extends PHPUnit_Framework_TestCase
{
    function testFunc()
    {
        $require = new Requirement;
        ok( $require );
        $require->classes( 'Universal\Requirement\RequirementTest' );

        $require->extensions('apc');
        $require->extensions('curl');
    }
}


<?php

class HttpResponseTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $response = new Universal\Http\HttpResponse(200);
        ok( $response );

        $response->finalize();
    }
}


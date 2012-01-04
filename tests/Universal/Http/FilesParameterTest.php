<?php 
namespace Universal\Http;
use PHPUnit_Framework_TestCase;
use Exception;

class FilesParameterTest extends PHPUnit_Framework_TestCase
{
    function testFunc()
    {

        $_FILES = array( );
        $_FILES['uploaded'] = array( 
            'name' => 'File1',
            'type' => 'text/plain',
            'size' => 100,
            'error' => 0
        );

        $req = new HttpRequest;
        ok( $req );
        ok( $_FILES );
        ok( $req->files->uploaded );
        is( 100, $req->files->uploaded->size );
        is( 'text/plain', $req->files->uploaded->type );
        is( 0, $req->files->uploaded->error );
    }
}


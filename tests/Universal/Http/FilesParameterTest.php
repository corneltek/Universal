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
            'tmp_name' => '/tmp/file1',
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

    function testFunc2()
    {
        $_FILES = array( );
        $_FILES['uploaded'] = array( 
            'name' => array( 'File1' , 'File2' ),
            'type' => array( 'text/plain', 'text/plain'),
            'size' => array( 100, 200 ),
            'error' => array( 0 , 0 ),
            'tmp_name' => array(  '/tmp/file1' , '/tmp/file2' ),
        );

        $req = new HttpRequest;
        ok( $req );
        ok( is_array( $req->files->uploaded ) );
    }
}


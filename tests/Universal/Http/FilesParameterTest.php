<?php 

function create_file_hash($files) {
    if( ! extension_loaded('Fileinfo') ) {
        throw new Exception('Fileinfo extension is required.');
    }

    $name     = array();
    $tmp_name = array();
    $size     = array();
    $type     = array();
    $error    = array();
    foreach( (array) $files as $file ) {
        $finfo = new finfo(FILEINFO_MIME);
        $ftype  = $finfo->file($file);
        $mime  = substr($ftype, 0, strpos($ftype, ';'));

        $name[]     = basename($file);
        $tmp_name[] = realpath($file);
        $size[]     = filesize($file);
        $type[]     = $mime;
        $error[]    = 0;
    }
    return array(
        'name'     => count($name) == 1 ? $name[0] : $name,
        'tmp_name' => count($tmp_name) == 1 ? $tmp_name[0] : $tmp_name,
        'size'     => count($size) == 1 ? $size[0] : $size,
        'type'     => count($type) == 1 ? $type[0] : $type,
        'error'    => count($error) == 1 ? $error[0] : $error,
    );
}

class HttpFilesParameterTest extends PHPUnit_Framework_TestCase
{
    function testFunc()
    {
        $_FILES = array( );
        $_FILES['uploaded'] = create_file_hash('tests/data/cat.txt');

        $req = new Universal\Http\HttpRequest;
        ok( $req );
        ok( $_FILES );
        ok( $req->files->uploaded );
        is( 11, $req->files->uploaded->size );
        is( 'text/plain', $req->files->uploaded->type );
        is( 0, $req->files->uploaded->error );

        ok( isset( $req->files['uploaded'] ) );
        $file = $req->files['uploaded'];
        ok( $file );
        isa_ok( 'Universal\Http\File' , $file );
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

        $req = new Universal\Http\HttpRequest;
        ok( $req );
        ok( is_array( $req->files->uploaded ) );

        foreach( $req->files->uploaded as $f ) {
            ok( $f );
            isa_ok( 'Universal\Http\File' , $f );
        }

        isa_ok( 'Universal\Http\Parameter', $req->post );
        isa_ok( 'Universal\Http\Parameter', $req->get );
    }
}


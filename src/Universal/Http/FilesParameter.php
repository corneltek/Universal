<?php 
namespace Universal\Http;

class FilesParameter extends Parameter
{
    public $hash;
    public $files;

    function __construct( $hash )
    {
        $files = array();
        foreach( array_keys($hash) as $name ) {

            // multiple files
            if( is_array($hash[ $name ][ 'error' ]) ) {
                $files[ $name ] = array();
                for( $i = 0 ; $i < count( $hash[ $name ]['error'] ) ; ++$i ) {
                    $attributes = array( 
                        'name' => $hash[$name]['name'],
                        'size' => $hash[$name]['size'],
                        'type' => $hash[$name]['type'],
                        'error' => $hash[$name]['error'],
                        'tmp_name' => $hash[$name]['tmp_name'],
                    );
                    $files[ $name ][] = new self( $attributes );
                }
            }
            else {
                // single file
                $files[ $name ] = new self( $hash[ $name ] );
            }
        }
        $this->hash = $files;

    }


        /*
        foreach ($_FILES["pictures"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
                $name = $_FILES["pictures"]["name"][$key];
                move_uploaded_file($tmp_name, "data/$name");
            }
        }
     */
}

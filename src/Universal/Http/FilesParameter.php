<?php 
namespace Universal\Http;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use SplFileInfo;

class FilesParameter extends Parameter
        implements ArrayAccess, IteratorAggregate
{

    function __construct( $hash = null )
    {
        if( !$hash && isset($_FILES) ) {
            $hash = $_FILES;
        }

        $files = array();
        foreach( array_keys($hash) as $name ) {

            // multiple files
            if( is_array($hash[ $name ][ 'error' ]) ) {
                $files[ $name ] = array();
                for( $i = 0 ; $i < count( $hash[ $name ]['error'] ) ; ++$i ) {
                    $attrs = array(
                        'name' => $hash[$name]['name'][ $i ],
                        'size' => $hash[$name]['size'][ $i ],
                        'type' => $hash[$name]['type'][ $i ],
                        'error' => $hash[$name]['error'][ $i ],
                        'tmp_name' => new SplFileInfo($hash[$name]['tmp_name'][ $i ]),
                    );
                    $files[ $name ][] = new File($attrs);
                }
            }
            else {
                // single file
                $hash[$name]['tmp_name'] = new SplFileInfo( $hash[$name]['tmp_name'] );
                $files[ $name ] = new File( $hash[ $name ] );
            }
        }
        $this->hash = $files;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->hash);
    }
    
    public function offsetSet($name,$value)
    {
        $this->hash[ $name ] = $value;
    }
    
    public function offsetExists($name)
    {
        return isset($this->hash[ $name ]);
    }
    
    public function offsetGet($name)
    {
        return $this->hash[ $name ];
    }
    
    public function offsetUnset($name)
    {
        unset($this->hash[$name]);
    }
    
}

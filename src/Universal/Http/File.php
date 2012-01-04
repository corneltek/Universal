<?php 
namespace Universal\Http;

class File extends Parameter
{

    public function __toString()
    {
        return @$this->hash[ 'name' ];
    }
}


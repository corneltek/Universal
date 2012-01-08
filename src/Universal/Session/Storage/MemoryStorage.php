<?php 
namespace Universal\Session\Storage;
class MemoryStorage
{
    protected $_data = array();


    public function setData($data)
    {
        $this->_data = $data;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function get($name) 
    {
        return isset( $this->_data[$name] ) ? $this->_data[ $name ] : null;
    }

    public function has($name)
    {
        return isset($this->_data[$name]);
    }

    public function set($name,$value)
    {
        $this->_data[ $name ] = $value;
    }
    
    public function delete($name)
    {
        unset($this->_data[ $name ]);
    }


}


<?php
/*
 * This file is part of the Universal package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Universal\Container;
use Exception;

class ObjectContainerException extends Exception {  }

class ObjectContainer 
{
    public $builders = array();

    public $_singletonObjects = array();

    public $throwIfNotFound = false;

    public function has($key)
    {
        return isset($this->builders[ $key ]);
    }

    public function singletonInstance($key)
    {
        if( isset( $this->_singletonObjects[ $key ] ) ) {
            return $this->_singletonObjects[ $key ];
        }
        elseif( $this->has($key) ) {
            return $this->_singletonObjects[ $key ] = $this->instance($key);
        }
        else {
            if( $this->throwIfNotFound ) {
                throw new ObjectContainerException("object builder not found: $key");
            }
        }
    }

    protected function _buildObject($b)
    {
        if( is_callable($b) ) {
            return call_user_func($b);
        } 
        elseif( is_array($b) ) {
            return call_user_func_array($b,array());
        }
        elseif( is_string($b) ) {
            $args = explode('#',$b);
            $class = $args[0];
            if( class_exists($class,true) ) {
                if( isset($args[1]) ) {
                    return call_user_func_array($args);
                } else {
                    return new $b;
                }
            }
            else {
                throw new ObjectContainerException("Can not build object from $b");
            }
        }
        return $b;
    }

    public function instance($key)
    {
        if( $builder = $this->getBuilder($key) ) {
            return $this->_buildObject($builder);
        }
    }

    public function getBuilder($key)
    {
        if( isset($this->builders[$key]) ) {
            return $this->builders[ $key ];
        }
    }

    public function setBuilder($key,$builder)
    {
        $this->builders[ $key ] = $builder;
    }

    public function __get($key)
    {
        return $this->singletonInstance($key);
    }

    public function __set($key,$builder) 
    {
        $this->setBuilder($key,$builder);
    }

    public function __isset($key)
    {
        return $this->has($key);
    }

}

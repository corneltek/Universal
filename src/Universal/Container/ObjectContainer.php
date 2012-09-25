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
    public $_builders = array();

    public $_singletonObjects = array();

    public $throwIfNotFound = false;


    /** 
     * Check if we have a builder.
     *
     * @param string $key builder key.
     */
    public function hasBuilder($key)
    {
        return isset($this->_builders[ $key ]);
    }


    /**
     * Register a singleton object builder
     *
     */
    public function instance($key,$args = array())
    {
        if( isset( $this->_singletonObjects[ $key ] ) ) {
            return $this->_singletonObjects[ $key ];
        }
        elseif( $this->hasBuilder($key) ) {
            return $this->_singletonObjects[ $key ] = $this->build($key,$args);
        }
        else {
            if( $this->throwIfNotFound ) {
                throw new ObjectContainerException("object builder not found: $key");
            }
        }
    }

    protected function _buildObject($b,$args = array())
    {
        if( is_callable($b) ) {
            return call_user_func_array($b,$args);
        } 
        elseif( is_array($b) ) {
            return call_user_func_array($b,$args);
        }
        elseif( is_string($b) ) {
            $callable = explode('#',$b);
            $class = $callable[0];
            if( class_exists($class,true) ) {
                if( isset($callable[1]) ) {
                    return call_user_func_array($callable,$args);
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

    public function build($key,$args = array())
    {
        if( $builder = $this->getBuilder($key) ) {
            return $this->_buildObject($builder, $args);
        }
    }


    /**
     * Get builder
     *
     * @param string $key
     */
    public function getBuilder($key)
    {
        if( isset($this->_builders[$key]) ) {
            return $this->_builders[ $key ];
        }
    }


    /**
     * Set object builder
     *
     * @param string $key
     * @param closure $builder
     */
    public function setBuilder($key,$builder)
    {
        $this->_builders[ $key ] = $builder;
    }

    public function __get($key)
    {
        return $this->instance($key);
    }

    public function __set($key,$builder) 
    {
        $this->setBuilder($key,$builder);
    }

    public function __isset($key)
    {
        return $this->hasBuilder($key);
    }

}

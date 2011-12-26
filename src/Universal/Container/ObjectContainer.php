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
class ObjectContainer 
{
    public $builders = array();
    public $_cachedObjects = array();

    private $_cache = true;

    function setCache($bool)
    {
        $this->_cache = $bool;
    }

    function __set($key,$builder) 
    {
        $this->builders[ $key ] = $builder;
    }

    function __get($key)
    {
        if( $this->_cache && isset( $this->_cachedObjects[ $key ] ) ) {
            return $this->_cachedObjects[ $key ];
        }
        elseif( isset( $this->builders[ $key ] ) ) {
            $b = $this->builders[ $key ];
            if( is_callable($b) ) {
                return $this->_cachedObjects[ $key ] = call_user_func($b);
            } else {
                return $this->_cachedObjects[ $key ] = $b;
            }
        }
        else {
            throw new Exception("Builder not found: $key");
        }
    }

}

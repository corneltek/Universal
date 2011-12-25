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

class ObjectContainer 
{
    public $builders = array();
    public $cachedObjects = array();

    public $cache = true;

    function setCache($bool)
    {
        $this->cache = $bool;
    }

    function __set($key,$builder) 
    {
        $this->builders[ $key ] = $builder;
    }

    function __get($key)
    {
        if( $this->cache && isset( $this->cachedObjects[ $key ] ) ) {
            return $this->cachedObjects[ $key ];
        }
        elseif( isset( $this->builders[ $key ] ) ) {
            $b = $this->builders[ $key ];
            if( is_callable( $b) ) {
                return $this->cachedObjects[ $key ] = call_user_func($b);
            } else {
                return $this->cachedObjects[ $key ] = $b;
            }
        }
        else {
            throw new Exception("Builder not found: $key");
        }
    }

}

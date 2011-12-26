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
namespace Universal\Event;

/**
 * A Simple PHP Event dispatcher
 */
class PhpEvent
{
    public $eventPool = array();

    function register($ev,$cb)
    {
        if( ! isset($this->eventPool[ $ev ] ) )
            $this->eventPool[ $ev ] = array();
        $this->eventPool[ $ev ][] = $cb;
    }

    function trigger($ev)
    {
        $args = func_get_args();
        array_shift( $args );

        if( isset( $this->eventPool[ $ev ] ) ) {
            foreach( $this->eventPool[ $ev ] as $cb ) {
                if( call_user_func_array( $cb , $args ) === false )
                    break;
            }
        }
    }

    static function getInstance()
    {
        static $instance;
        return $instance ? $instance : $instance = new static;
    }

}

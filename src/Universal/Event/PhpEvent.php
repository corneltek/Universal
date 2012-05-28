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
 *
 *
 * TODO: Move this PhpEvent to a standard-alone component.
 */
class PhpEvent
{

    /**
     * Event pool
     *
     * @var array save event callbacks
     */
    public $eventPool = array();

    /**
     * Register event name
     *
     * @param string $ev
     * @param closure $cb callable function
     */
    function register($ev,$cb)
    {
        if( ! isset($this->eventPool[ $ev ] ) )
            $this->eventPool[ $ev ] = array();
        $this->eventPool[ $ev ][] = $cb;
    }


    /**
     * trigger event with event name
     *
     * @param string $ev event name
     */
    public function trigger($ev)
    {
        $results = array();
        if( isset( $this->eventPool[ $ev ] ) ) {
            $args = func_get_args();
            array_shift( $args );
            foreach( $this->eventPool[ $ev ] as $cb ) {
                /**
                 * to break the event trigger, just return false.
                 */
                $ret = call_user_func_array( $cb , $args );
                if( $ret === false )
                    break;
                $results[] = $ret;
            }
        }
        return $results;
    }


    /**
     * clear event pool
     */
    public function clear()
    {
        // clear event pool
        $this->eventPool = array();
    }


    /**
     * static singleton method
     */
    static function getInstance()
    {
        static $instance;
        return $instance ? $instance : $instance = new static;
    }

}

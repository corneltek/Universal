<?php
require '../init.php';
use Universal\Container\ObjectContainer as Container;

$container = new Container;
$container->state = function() {
    return new Universal\Session\State\NativeState;
};
$container->storage = function() {
    return new Universal\Session\Storage\NativeStorage;
};

$session = new Universal\Session\Session;
$counter = $session->get( 'counter' );
$session->set( 'counter' , ++$counter );
echo $session->get( 'counter' );

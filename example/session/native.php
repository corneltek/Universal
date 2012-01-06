<?php
require '../init.php';

/*
$session = new Universal\Session\Session(array(  
    'state'   => new Universal\Session\State\NativeState,
    'storage' => new Universal\Session\Storage\NativeStorage,
));
*/

$session = new Universal\Session\Session;
$counter = $session->get( 'counter' );
$session->set( 'counter' , ++$counter );
echo $session->get( 'counter' );

$counter = $session->counter;
$session->counter += 2;

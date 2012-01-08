<?php 
require '../init.php';
$session = new Universal\Session\Session(array(  
    'state'   => new Universal\Session\State\CookieState,
    'storage' => new Universal\Session\Storage\MemcacheStorage,
));
$counter = $session->get( 'counter' );
$session->set( 'counter' , ++$counter );
echo $session->get( 'counter' );

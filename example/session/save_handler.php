<?php
require '../init.php';

$session = new Universal\Session\Session(array(  
    'save_handler' => new Universal\Session\SaveHandler\MemcacheSaveHandler,
));
$counter = $session->get( 'counter' );
$session->set( 'counter' , ++$counter );
echo $session->get( 'counter' );

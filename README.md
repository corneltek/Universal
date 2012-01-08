Universal
=========

Universal is a general proprose PHP library, includes these items beloew

- ClassLoaders
- FileUtils
- HTTPRequest handler

## Classloader

### SplClassLoader

    use Universal\ClassLoader\SplClassLoader;
    $loader = new \UniversalClassLoader\SplClassLoader( array(  
            'Vendor\Onion' => 'path/to/Onion',
            'Vendor\CLIFramework' => 'path/to/CLIFramework',
    ));
    $loader->addNamespace(array( 
        'NS' => 'path'
    ));
    $loader->useIncludePath();
    $loader->register();

### BasePathClassLoader

    $loader = new BasePathClassLoader( array( 
        'vendor/pear', 'external_vendor/src'
    ) );
    $loader->useEnvPhpLib();
    $loader->register();

### Include Path Manipulator

Include Path manipulator
 
    $includer = new PathIncluder(array( 'to/path', ... ));
    $includer->add( 'path/to/lib' );
    $includer->setup();   // write set_include_path

## HttpRequest

For multiple files:

    $req = new HttpRequest;
    foreach( $req->files->uploaded as $f ) {
        $extname = $f->getExtension();
        $filename = $f->getPathname();
    }

    $req->param( 'username' );   // get $_REQUEST['username'];

    $req->get->username;    // get $_GET['username'];

    $req->post->username;   // get $_POST['username'];

    $req->server->path_info;  // get $_SERVER['path_info'];


## Session

Supported Session Storage backend:

- Memcache
- Redis
- Native

use ObjectContainer to pass options:

    $container = new Universal\Container\ObjectContainer;
    $container->state = function() {
        return new Universal\Session\State\NativeState;
    };
    $container->storage = function() {
        return new Universal\Session\Storage\NativeStorage;
    };

Native Session:

    $session = new Universal\Session\Session(array(  
        'state'   => new Universal\Session\State\NativeState,
        'storage' => new Universal\Session\Storage\NativeStorage,
    ));
    $counter = $session->get( 'counter' );
    $session->set( 'counter' , ++$counter );
    echo $session->get( 'counter' );

Session with memcache backend:

    $session = new Universal\Session\Session(array(  
        'state'   => new Universal\Session\State\CookieState,
        'storage' => new Universal\Session\Storage\MemcacheStorage,
    ));
    $counter = $session->get( 'counter' );
    $session->set( 'counter' , ++$counter );
    echo $session->get( 'counter' );

## Event

    use Universal\Event\PhpEvent;
    $e = PhpEvent::getInstance();

    // register your handler
    $e->register( 'test', function($a,$b,$c) {
        // do what you want

    });

    // trigger event handlers
    $e->trigger( 'test' , 1,2,3  );

## Requirement Checker

    try {
        $require = new Universal\Requirement\Requirement;
        $require->extensions( 'apc','mbstring' );
        $require->classes( 'ClassName' , 'ClassName2' );
        $require->functions( 'func1' , 'func2' , 'function3' )
    }
    catch( RequireExtensionException $e ) {

    }
    catch( RequireFunctionException $e ) {

    }
    catch( RequireClassException $e ) {

    }

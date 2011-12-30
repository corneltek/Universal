<?php 
namespace Universal\Requirement;

use Exception;

class RequirePhpException extends Exception {}
class RequireExtensionException extends Exception {} 
class RequireFunctionException extends Exception {}
class RequireClassException extends Exception {}

/**
 * Requirement check class
 *
 * @code
 *
 * try {
 *      $require = new Universal\Requirement\Requirement;
 *      $require->extensions( 'apc','mbstring' );
 *      $require->classes( 'ClassName' , 'ClassName2' );
 *      $require->functions( 'func1' , 'func2' , 'function3' )
 * }
 * catch( RequireExtensionException $e ) {
 * }
 * catch( RequireFunctionException $e ) {
 * }
 * catch( RequireClassException $e ) {
 * }
 *
 */
class Requirement
{

    function php($version)
    {
        if( version_compare( phpversion() , $version ) < 0 ) {
            throw new RequirePhpException( "PHP Version $version is required." );
        }
        return true;
    }

    function extensions()
    {
        $extensions = func_get_args();
        foreach( $extensions as $extensionName ) {
            if( ! extension_loaded( $extensionName ) )
                throw new RequireExtensionException( "Extension $extensionName is required" );
        }
        return true;
    }

    function functions()
    {
        $functions = func_get_args();
        foreach( $functions as $function ) {
            if( ! function_exists( $function ) )
                throw new RequireFunctionException( "Function $function is required" );
        }
        return true;
    }

    function classes()
    {
        $classes = func_get_args();
        foreach( $classes as $class ) {
            if( ! class_exists( $class ) )
                throw new RequireClassException( "Class $class is required" );
        }
        return true;
    }
}

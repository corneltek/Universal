<?php 
namespace Universal\Http;

/**
 * $req = new HttpRequest;
 * $v = $req->get->varname;
 * $b = $req->post->varname;
 *
 * $username = $req->param('username');
 *
 * $req->files->uploaded->name;
 * $req->files->uploaded->size;
 * $req->files->uploaded->tmp_name;
 * $req->files->uploaded->error;
 */
class HttpRequest
{
    private $requestVars = array();

    function __construct()
    {

    }

    function __get( $name )
    {
        return $this->getParameters( $name );
    }

    function param($name)
    {
        if( isset($_REQUEST[ $name ]) )
            return $_REQUEST[ $name ];
    }

    function getParameters( & $name )
    {
        if( isset($this->requestVars[ $name ]) ){
            return $this->requestVars[ $name ];
        }

        $vars = null;
        switch( $name )
        {
            case 'files':
                $vars = new FilesParameter($_FILES);
                break;
            case 'post':
                $vars = new Parameter($_POST);
                break;
            case 'get':
                $vars = new Parameter($_GET);
                break;
            case 'session':
                $vars = new Parameter($_SESSION);
                break;
            case 'server':
                $vars = new Parameter($_SERVER);
                break;
            case 'request':
                $vars = new Parameter($_REQUEST);
                break;
            case 'cookie':
                $vars = new Parameter($_COOKIE);
                break;
        }
        return $this->requestVars[ $name ] = $vars;
    }

}

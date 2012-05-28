<?php 
namespace Universal\Http;
use ArrayAccess;

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
    implements ArrayAccess
{
    private $requestVars = array();

    /**
     * ->get->key
     * ->post->key
     * ->session->key
     * ->cookie->key
     */
    function __get( $name )
    {
        return $this->getParameters( $name );
    }



    /**
     * Get request body 
     *
     * @return string
     */
    function getInput()
    {
        return file_get_contents('php://input');
    }


    /**
     * Parse submited body content return parameters
     *
     * @return array parameters
     */
    function getInputParams()
    {
        $params = array();
        parse_str( $this->getInput() , $params );
        return $params;
    }




    /**
     * Check if we have the parameter
     *
     * @param string $name parameter name
     */
    function hasParam($name)
    {
        return isset($_REQUEST[$name]);
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

    


    public function offsetSet($name,$value)
    {
        $_REQUEST[ $name ] = $value;
    }
    
    public function offsetExists($name)
    {
        return isset($_REQUEST[ $name ]);
    }
    
    public function offsetGet($name)
    {
        return $_REQUEST[ $name ];
    }
    
    public function offsetUnset($name)
    {
        unset($_REQUEST[$name]);
    }

}


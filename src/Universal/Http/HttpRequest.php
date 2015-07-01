<?php 
namespace Universal\Http;
use ArrayAccess;
use Universal\Http\FilesParameter;

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
    protected $requestVars = array();

    protected $files = array();

    protected $parameters = array();


    /**
     * When $parameters is defined, HttpRequest uses $parameters instead of the default $_REQUEST
     * When $files is ignored, HttpRequest uses $_FILES as the default file array.
     *
     * @param array|null $parameters The array of request parameter, usually $_REQUEST
     * @param array|null $files The array of files, usually $_FILES
     */
    public function __construct(array $parameters = null, array $files = null)
    {
        if ($parameters) {
            $this->parameters = $parameters;
        } else if (isset($_REQUEST)) {
            $this->parameters = $_REQUEST;
        } else {
            $this->parameters = array();
        }
        if ($files) {
            $this->files = FilesParameter::fix_files_array($files);
        } else {
            $this->files = FilesParameter::fix_files_array($_FILES);
        }
    }

    /**
     * Check if we have the parameter
     *
     * @param string $name parameter name
     */
    public function hasParam($name)
    {
        return isset($this->parameters[$name]);
    }

    public function param($field)
    {
        if (isset($this->parameters[$field])) {
            return $this->parameters[$field];
        }
    }

    public function file($field)
    {
        if (isset($this->files[$field])) {
            return $this->files[$field];
        }
    }


    /**
     * ->get->key
     * ->post->key
     * ->session->key
     * ->cookie->key
     */
    public function __get( $name )
    {
        return $this->getParameters( $name );
    }

    /**
     * Get request body if any
     *
     * @return string
     */
    public function getInput()
    {
        return file_get_contents('php://input');
    }


    /**
     * Parse submited body content return parameters
     *
     * @return array parameters
     */
    public function getInputParams()
    {
        $params = array();
        parse_str($this->getInput(), $params);
        return $params;
    }



    public function getParameters( & $name )
    {
        if (isset($this->requestVars[ $name ])) {
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
        $this->parameters[ $name ] = $value;
    }
    
    public function offsetExists($name)
    {
        return isset($this->parameters[ $name ]);
    }
    
    public function offsetGet($name)
    {
        return $this->parameters[ $name ];
    }
    
    public function offsetUnset($name)
    {
        unset($this->paramemters[$name]);
    }

}


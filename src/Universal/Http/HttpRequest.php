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



    /**
     * @var array parameters from $_FILES
     */
    protected $files = array();


    /**
     * @var array parameters from $_REQUEST
     */
    protected $parameters = array();


    /**
     * @var array parameters parsed from POST request method
     */
    protected $bodyParameters = array();

    /**
     * @var array parameters parsed from query string
     */
    protected $queryParameters = array();


    protected $cookies = array();


    /**
     * @var array parameters created from $_SERVER
     */
    protected $serverParameters = array();

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
        }
        if ($files) {
            $this->files = FilesParameter::fix_files_array($files);
        } else if (isset($_FILES)) {
            $this->files = FilesParameter::fix_files_array($_FILES);
        }
    }

    /**
     * Check if we have the parameter
     *
     * @param string $name parameter name
     * @return boolean
     */
    public function hasParam($name)
    {
        return isset($this->parameters[$name]);
    }

    /**
     * @param string $field parameter field name
     */
    public function param($field)
    {
        if (isset($this->parameters[$field])) {
            return $this->parameters[$field];
        }
    }

    public function getFiles() 
    {
        return $this->files;
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


    public function getParameters()
    {
        return $this->parameters;
    }

    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    public function getBodyParameters()
    {
        return $this->bodyParameters;
    }


    /**
     * Create request object from superglobal $GLOBALS
     *
     * @param $globals The $GLOBALS
     * @return HttpRequest
     */
    static public function createFromGlobals(array $globals)
    {
        $request = new self;
        if (isset($globals['_POST'])) {
            $request->bodyParameters = $globals['_POST'];
        }
        if (isset($globals['_GET'])) {
            $request->queryParameters = $globals['_GET'];
        }
        if (isset($globals['_REQUEST'])) {
            $request->parameters = $globals['_REQUEST'];
        }
        if (isset($globals['_COOKIE'])) {
            $request->cookies = $globals['_COOKIE'];
        }
        if (isset($globals['_FILES'])) {
            $request->files = FilesParameter::fix_files_array($globals['_FILES']);
        }
        if (isset($globals['_SERVER'])) {
            $request->serverParameters = $globals['_SERVER'];
        }
        return $request;
    }






}


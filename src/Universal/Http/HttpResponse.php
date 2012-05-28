<?php
namespace Universal\Http;

class HttpResponse
{
    public $code;

    public $status; /* status message for Code */

    public $contentType;

    public $body;

    public function __construct($code = 200, $msg = 'OK') 
    {
        $this->code = $code;
        $this->status = $msg;
    }


    public function code($code)
    {
        $this->code = $code;
    }

    public function status($status)
    {
        $this->status = $status;
    }

    public function location($url)
    {
        header( 'Location: ' . $url );
    }

    /**
     * Redirect to URL (Temporarily)
     *
     * @param string $url
     */
    public function redirect($url) 
    {
        $this->code(302);
        $this->location($url);
    }


    /**
     * Redirect permanently
     *
     * @param string $url
     */
    public function redirectPermanently($url)
    {
        $this->code(301);
        $this->status('Moved Permanently');
        $this->location($url);
    }

    /**
     * Redirect to URL (delayed)
     *  
     * @param string $url
     * @param integer $seconds (default = 1)
     */
    public function redirectLater($url, $seconds = 1) 
    {
        header( "refresh: $seconds; url=$url" );
    }


    /**
     * set content type
     *
     * @param string $contentType eg. text/html
     *
     * @code
     *
     *     $response->contentType('text/html');
     *
     * @endcode
     */
    public function contentType($contentType)
    {
        $this->contentType = $contentType;
        header( "Content-type: $contentType" );
    }

    public function body($body)
    {
        $this->body = $body;
    }


    /**
     * Set cache-control to header
     *
     * @param string $desc cache control string
     */
    public function cacheControl($desc) 
    {
        header('Cache-Control: '  . $desc); // HTTP/1.1
    }


    /**
     * Set cache-control to no-cahche
     */
    public function noCache() 
    {
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    }

    /**
     * Set cache expiry time
     */
    public function cacheExpiryTime($seconds) 
    {
        $time = time() + $seconds;
        $datestr = gmdate(DATE_RFC822, $time );
        // header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        header( "Expires: $datestr" );
    }


    /**
     * HTTP Status Code Helper Methods
     *
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     * @link http://restpatterns.org/HTTP_Status_Codes
     *
     * REST Pattern
     * @link http://restpatterns.org/
     */
    public function codeOk() 
    {
        $this->code = 200;
        $this->status = 'OK';
    }

    public function codeCreated()
    {
        header('HTTP/1.1 201 Created');
        $this->code = 201;
        $this->status = 'Created';
    }

    public function codeAccepted()
    {
        $this->code = 202;
        $this->status = 'Accepted';
    }

    public function codeNoContent() 
    {
        $this->code = 204;
        $this->status = 'No Content';
    }

    public function codeBadRequest()
    {
        $this->code = 400;
        $this->status = 'Bad Request';
    }

    public function codeForbidden()
    {
        $this->code = 403;
        $this->status = 'Forbidden';
    }

    public function codeNotFound()
    {
        $this->code = 404;
        $this->status = 'Not found';
    }

    public function __toString() 
    {
        header('HTTP/1.1 ' . $this->code . ' ' . $this->status );
        if( $this->contentType ) {
            header("Content-type: " . $this->contentType );
        }
        echo $this->body;
    }

}




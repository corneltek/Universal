<?php
namespace Universal\Exception;
use Exception;

class UploadException extends Exception
{
    protected $file = array();

    public function __construct(array $filestash, $message, $code = 0, $previous = null) {
        $this->file = $filestash;
        parent::__construct($message, $code, $previous);
    }

    public function __debugInfo() {
        return [
            'file' => $this->file,
            'message' => $this->message,
        ];
    }
}





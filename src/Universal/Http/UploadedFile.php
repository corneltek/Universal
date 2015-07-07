<?php
namespace Universal\Http;
use Exception;

/**
    $f = new Universal\Http\UploadedFile(array( 
        'name' => 'filename',
        'tmp_name' => '/tmp/123fbffef',
        'type' => 'image/jpg',
        'size' => 33300,
    ));
    $f->putIn( "file_dirs" );
*/
class UploadedFile
{
    /**
     * The original filename in $_FILES
     *
     * @var string
     */
    public $originalFileName;


    /**
     * The path of tmp file
     *
     * @var string
     */
    public $tmpName;

    public $type;

    public $size;

    public $error;

    public $savedPath;

    protected $stash = array();

    public function __construct()
    {

    }

    static public function createFromArray(array & $stash) 
    {
        $file = new self;
        if (isset($stash['tmp_name'])) {
            $file->tmpName = $stash['tmp_name'];
        }
        if (isset($stash['name'])) {
            $file->originalFileName = $stash['name'];
        }
        if (isset($stash['type'])) {
            $file->type = $stash['type'];
        }
        if (isset($stash['size'])) {
            $file->size = $stash['size'];
        }
        if (isset($stash['saved_path'])) {
            $file->savedPath = $stash['saved_path'];
        }
        $file->setStashedArray($stash);
        return $file;
    }

    protected function setStashedArray(array & $stash)
    {
        $this->stash = $stash;
    }

    protected function getStashedArray()
    {
        return $this->stash;
    }

    /**
     * getCurrentPath returns the current target file.
     *
     * Before move_uploaded_file function call, it returns $file->tmp_name
     * After move_uploaded_file  function call, it returns $file->savedPath
     *
     */
    public function getCurrentPath()
    {
        if ($this->savedPath) {
            return $this->savedPath;
        }
        return $this->tmpName;
    }

    public function getOriginalFileName()
    {
        return $this->originalFileName; 
    }

    public function getExtension()
    {
        $parts = explode('.',$this->originalFileName);
        return end($parts);
    }

    /* size: kbytes */
    public function validateSize($size)
    {
        return ($this->size / 1024) < $size;
    }

    public function validateExtension(array $exts)
    {
        $ext = strtolower($this->getExtension());
        return in_array($ext, $exts);
    }

    public function getSavedPath()
    {
        return $this->savedPath;
    }

    public function getType() 
    { 
        return $this->type;
    }

    public function getSize() 
    {
        return $this->size;
    }

    /**
     * moveTo method doesn't modify tmp_name attribute
     * rather than that, we set the saved_path attribute
     * for location of these moved files.
     *
     * @return path|boolean 
     *
     * return FALSE when operation failed.
     *
     * return path string if the operation succeeded.
     */
    public function moveTo($targetDir, $rename = false)
    {
        if ($this->savedPath) {
            return $this->savedPath;
        }

        $tmpFile = $this->tmpName;

        // if targetFilename is not given,
        // we should take the filename from original filename by using basename.
        $targetFileName = basename($this->originalFileName);

        // relative file path.
        $newPath = $targetDir . DIRECTORY_SEPARATOR . $targetFileName;

        // Avoid file name duplication 
        /*
        $fileCnt = 1;
        while (file_exists($newPath)) {
            $newPath = 
                $targetDir . DIRECTORY_SEPARATOR . 
                    FileUtils::filename_suffix( $targetFileName , '_' . $fileCnt++ );
        }
         */
        $ret = false;
        if ($rename) {
            $ret = rename($tmpFile, $newPath);
        } else {
            $ret = $this->move($tmpFile, $newPath );
        }
        $this->savedPath = $this->stash['saved_path'] = $newPath;

        if ($ret === false) {
            return $ret;
        }

        // $_FILES[ $this->column ]['saved_path'] = $newPath;
        return $newPath;
    }

    public function move($target)
    {
        // file already moved
        if (isset($this->savedPath)) {
            return false;
        }
        if ($this->stash['error'] != 0 ) {
            throw new Exception('File Upload Error:' . $this->getErrorMessage() );
        }
        if (false === move_uploaded_file($this->tmpName, $target)) {
            throw new Exception('File upload error: move uploaded file failed.');
        }
        return $this->savedPath = $this->stash['saved_path'] = $moveTo;
    }

    public function deleteTmp()
    {
        unlink( $this->tmpName );
    }

    public function found()
    {
        return $this->name ? true : false;
    }

    public function hasError()
    {
        return (bool) $this->error;
    }

    public function getErrorMessage()
    {
        $error = $this->error;

        // error messages for normal users.
        switch ($error) {
            case UPLOAD_ERR_OK:
                return _("No Error");
            case UPLOAD_ERR_INI_SIZE || UPLOAD_ERR_FORM_SIZE:
                return _("The upload file exceeds the limit.");
            case UPLOAD_ERR_PARTIAL:
                return _("The uploaded file was only partially uploaded.");
            case UPLOAD_ERR_NO_FILE:
                return _("No file was uploaded.");
            case UPLOAD_ERR_CANT_WRITE:
                return _("Failed to write file to disk.");
            case UPLOAD_ERR_EXTENSION:
                return _("A PHP extension stopped the file upload.");
            default:
                return _("Unknown Error.");
        }

        // built-in php error description
        switch ($error) {
            case UPLOAD_ERR_OK:
                return _("There is no error, the file uploaded with success.");
            case UPLOAD_ERR_INI_SIZE:
                return _("The uploaded file exceeds the upload_max_filesize directive in php.ini.");
            case UPLOAD_ERR_FORM_SIZE:
                return _("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.");
            case UPLOAD_ERR_PARTIAL:
                return _("The uploaded file was only partially uploaded.");
            case UPLOAD_ERR_NO_FILE:
                return _("No file was uploaded.");
            case UPLOAD_ERR_NO_TMP_DIR:
                return _("Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.");
            case UPLOAD_ERR_CANT_WRITE:
                return _("Failed to write file to disk. Introduced in PHP 5.1.0.");
            case UPLOAD_ERR_EXTENSION:
                return _("A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0.");
            default:
                return _("Unknown Error.");
        }
    }

}

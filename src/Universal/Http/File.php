<?php 
namespace Universal\Http;
use SplFileInfo;

class File extends Parameter
{

    /**
     * If there is a file and no error
     *
     * @return boolean
     */
    public function hasFile()
    {
        return isset( $this->hash['tmp_name'] ) 
            && $this->hash['tmp_name'] 
            && file_exists($this->hash['tmp_name'])
            && $this->hash['error'] == UPLOAD_ERR_OK;
    }



    /**
     * Move temporary file to a path
     *
     * @param string $filepath can be file or directory path.
     */
    public function move( $filepath , $as = null )
    {
        if( is_dir( $filepath ) ) {
            $filepath = $filepath 
                . DIRECTORY_SEPARATOR . ($as ?: $this->hash['name']);
            if( false !== move_uploaded_file( $this->hash['tmp_name'], $filepath) ) {
                $this->hash['saved_name'] = $filepath;
                return true;
            }
        } else {
            if( false !== move_uploaded_file( $this->hash['tmp_name'], $filepath) ) {
                $this->hash['saved_name'] = $filepath;
                return true;
            }
        }
        return false;
    }


    /**
     * Not to move file, but copy
     */
    public function copy( $filepath , $as = null )
    {
        if( is_dir( $filepath ) ) {
            $filepath = $filepath 
                . DIRECTORY_SEPARATOR . ($as ?: $this->hash['name']);
            if( copy( $this->hash['tmp_name'], $filepath ) !== false ) {
                $this->hash['saved_name'] = $filepath;
                return true;
            }
        } else {
            if( false !== copy( $this->hash['tmp_name'], $filepath ) ) {
                $this->hash['saved_name'] = $filepath;
                return true;
            }
        }
        return false;
    }


    /**
     * delete temp file
     */
    public function delete()
    {
        unlink( $this->hash['tmp_name'] );
    }


    /**
     * use pathinfo function to parse path info from filename.
     *
     * @return array pathinfo
     */
    public function getPathInfo()
    {
        static $info;
        return $info ?: $info = pathinfo( $this->hash['name'] );
    }


    /**
     * return filename extension
     */
    public function getExtension()
    {
        $info = $this->getPathInfo();
        return $info['extension'];
    }


    public function getKBytes() 
    {
        return (int) $this->hash['size'] / 1024;
    }
    

    public function validateExtension( $exts )
    {
        $ext = strtolower($this->getExtension());
        return in_array( $ext, $exts );
    }

    /**
     * Get tmp_name filepath
     */
    public function getFilepath()
    {
        return $this->hash['tmp_name'];
    }


    public function getSavedFilepath()
    {
        return $this->hash['saved_name'];
    }


    /**
     * Get upload filename
     */
    public function getFilename()
    {
        $info = $this->getPathInfo();
        return $info['basename'];   // filename is "only" filename, basename is the "filename".
    }


    /**
     * Convert current tempfile to SplFileInfo object
     */
    public function asSplFileInfo()
    {
        return new SplFileInfo( $this->hash['tmp_name'] );
    }




    /**
     * Error checking methods
     */

    /**
     * is upload successed ?
     */
    public function isSuccess()
    {
        return $this->hash['error'] == UPLOAD_ERR_OK;
    }

    public function isErrorPartial()
    {
        return $this->hash['error'] == UPLOAD_ERR_PARTIAL;
    }

    public function isErrorNoFile()
    {
        return $this->hash['error'] == UPLOAD_ERR_NO_FILE;
    }

    public function isErrorCantWrite()
    {
        return $this->hash['error'] == UPLOAD_ERR_CANT_WRITE;
    }

    public function isErrorExceedSize()
    {
        return $this->hash['error'] == UPLOAD_ERR_INI_SIZE
            || $this->hash['error'] == UPLOAD_ERR_FORM_SIZE;
    }

    public function isError()
    {
        return $this->hash['error'] != UPLOAD_ERR_OK;
    }

    /**
     * return error code
     */
    public function getErrorCode()
    {
        return $this->hash['error'];
    }

    /**
     * Return pretty format size
     *
     * TODO: Move this to pretty size.
     */
    public function getPrettySize()
    {
        $size = $this->hash['size'];
        $kb = 1024;
        $mb = 1024 * 1024;
        $gb = 1024 * 1024 * 1024;

        if( $size < $kb ) {
            return $size . ' Bytes';
        } elseif( $size < $mb ) { 
            return $size / $kb . ' KB';
        } elseif( $size < $gb ) {
            return $size / $mb . ' MB';
        }
        return $size / $gb . ' GB';
    }

    public function isSizeExceed( $limitSize )
    {
        if( is_string( $limitSize ) ) {
            // parse size string
            if( preg_match( '/[0-9.]+\s*[MGK]B?/i' , $limitSize , $regs ) ) {
                $n = intval($regs[1]);
                $unit = strtolower( $regs[2] );
                switch( $unit ) 
                {
                case 'm':
                    return ( $this->hash['size'] > $n * 1024 * 1024 );
                    break;
                case 'k':
                    return ( $this->hash['size'] > $n * 1024 );
                    break;
                case 'g':
                    return ( $this->hash['size'] > $n * 1024 * 1024 * 1024 );
                    break;
                default:
                    // check as bytes
                    return ( $this->hash['size'] > $n );
                    break;
                }
            }
        }
    }

    /**
     * return upload error message
     */
    public function getErrorMessage()
    {
        $error = $this->hash['error'];

        // error messages for normal users.
        switch( $error ) {
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
        switch( $error ) {
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


    public function __toString()
    {
        return @$this->hash[ 'name' ];
    }

}


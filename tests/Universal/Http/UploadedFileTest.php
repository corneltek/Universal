<?php
use Universal\Http\UploadedFile;

class UploadedFileTest extends PHPUnit_Framework_TestCase
{
    public function testMove()
    {
        $tmpfile = tempnam('/tmp', 'test_');
        $this->assertNotFalse(file_put_contents($tmpfile, 'foo'));

        $filestash = array( 
            'name' => 'filename.txt',
            'tmp_name' => $tmpfile,
            'type' => 'text/plain',
            'size' => filesize($tmpfile),
            'error' => 0,
        );
        $file = UploadedFile::createFromArray($filestash);
        $this->assertNotNull($file);
        $this->assertInstanceOf('Universal\Http\UploadedFile', $file);
        $ret = $file->moveTo('tests', true);
        $this->assertEquals('tests/filename.txt', $ret);
    }
}


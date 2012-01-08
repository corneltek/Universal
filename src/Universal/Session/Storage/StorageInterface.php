<?php 
namespace Universal\Session\Storage;

interface StorageInterface
{

    public function get($name);

    public function set($name,$value);

    public function has($name);

    public function delete($name);

    public function destroy();

    public function sync();

    public function load($sessionId);

}

<?php 
namespace Universal\Session\SessionStorage;

interface StorageInterface
{

    public function get($name);

    public function set($name,$value);

    public function has($name);

    public function delete($name);

    public function destroy();

}

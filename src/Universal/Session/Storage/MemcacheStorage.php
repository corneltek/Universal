<?php 
namespace Universal\Session\Storage;

class MemcacheStorage
    extends MemoryStorage
    implements StorageInterface
{
    private $connection;
    private $sessionId;
    private $ttl = 0;

    /**
     * bool Memcache::addServer ( string $host [, int $port = 11211 
     *    [, bool $persistent [, int $weight [, int $timeout [, int $retry_interval [, 
     *    bool $status [, callback $failure_callback [, int $timeoutms ]]]]]]]] )
     */
    function __construct($host = '127.0.0.1', $port = 11211, 
        $persistent = true, 
        $weight = 1,
        $timeout = 1 )
    {
        /*
         Another example:
            $session_save_path = "tcp://$host:$port?persistent=1&weight=2&timeout=2&retry_interval=10,  ,tcp://$host:$port  ";
            ini_set('session.save_handler', 'memcache');
            ini_set('session.save_path', $session_save_path);
        */
        $this->connection = new \Memcache;
        $this->connection->addServer( $host, $port, $persistent, $weight, $timeout );
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }

    public function load($sessionId)
    {
        $this->sessionId = $sessionId;
        $data = $this->connection->get( $sessionId ) ?: array();
        $this->setData( $data );
    }

    /**
     * sync memory data to backend
     */
    public function sync()
    {
        $this->connection->set( $this->sessionId , $this->_data );
    }

    public function destroy()
    {

    }

    public function __destruct()
    {
        $this->sync();
    }

}



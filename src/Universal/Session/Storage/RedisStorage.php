<?php 
namespace Universal\Session\Storage;
use Redis;

class RedisStorage 
    extends MemoryStorage
    implements StorageInterface
{

    /**
     * can use session handler to hook redis session storage
     *
     *  ini_set('session.save_handler', redis);
     *  ini_set('session.save_path',"tcp://host1:6379?weight=1, tcp://host2:6379?weight=2&timeout=2.5, tcp://host3:6379?weight=2");
     */
    private $connection;

    private $sessionId;


    /**
     * function prototype is inherited from Redis constructor
     */
    public function __construct()
    {
        $args = func_get_args();
        $this->connection = new Redis;

        if( ! empty($args) ) {
            call_user_func_array(array( $this->connection, 'pconnect') , $args );
        } else {
            $this->connection->pconnect( '127.0.0.1', 6379);
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function load($sessionId)
    {
        $this->sessionId = $sessionId;
        $datastr = $this->connection->get( $sessionId );
        $data = $datastr ? unserialize($datastr) : array();
        $this->setData( $data );
    }

    public function destroy()
    {
        $this->connection->delete( $this->sessionId );
    }

    /**
     * sync memory data to backend
     */
    public function sync()
    {
        $this->connection->set( $this->sessionId , serialize($this->_data) );
    }


    public function __destruct()
    {
        $this->sync();
    }

}

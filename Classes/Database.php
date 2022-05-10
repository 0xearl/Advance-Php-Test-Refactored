<?php 

namespace Classes;

class Database 
{
    private static $instance;
    public $connection;
    private $host = 'localhost';
    private $user = 'root';
    private $db_password = '';
    private $db_name = 'nba2019';

    public function __construct()
    {
        $this->connection = new \mysqli($this->host, $this->user, $this->db_password, $this->db_name);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance() 
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function getConnection() {
        return $this->connection;
    }
}

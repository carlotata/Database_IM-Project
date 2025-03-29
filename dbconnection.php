<?php
class Database
{
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "myproject";

    private $conn;
    private $errMsg;
    private $state; 

    public function __construct()
    {
        try {
            $this->conn = new  PDO("mysql:host=" . $this->server . ";dbname=" .$this->dbname, $this->username, $this->password);
            $this->conn->exec('set names utf8');
            $this->errMsg = "Connected";
            $this->state = true;
        } catch (PDOException $e) {
            $this->state = false;
            $this->errMsg = "Error :" . $e->getMessage();
        }
    }

    public function getState()
    {
        return $this->state;
    }
    public function getErrMsg()
    {
        return $this->errMsg;
    }
    public function getDb()
    {
        return $this->conn;
    }
}
?>
<?php
class database
{
    private $host;
    private $user;
    private $pass;
    private $dbms;
    private $conn;
    private $stat;

    function __construct()
    {
        $this->host = "localhost";
        $this->user = "root";
        $this->pass = "";
        $this->dbms = "grade";
        $this->stat = false;
        $this->conn = $this->init();
    }
    public function getStatus(){
        return $this->stat;
    }
    public function getCon(){
        return $this->conn;
    }
    public function closeConnection(){
        $this->conn = null;
    }
    private function init()
    {
        try {
            $conn = new PDO("mysql:host=$this->host;dbname=" . $this->dbms, $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->stat = true;
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
?>
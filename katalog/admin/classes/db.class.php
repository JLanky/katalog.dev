<?php

class Db
{
    private $host = 'localhost';
    private $database = 'test';
    private $name = 'root';
    private $pass = 'root';
    public $dsn;
    private $bConnected = false;
    private $result;
    private $pdo;
    private $query;
    private $param;
    private $charset = 'utf8';

    public function __construct($host='localhost', $name='root', $pass='root', $db='test')
    {
        $this->host = $host;
        $this->name = $name;
        $this->pass = $pass;
        $this->database = $db;
        $this->connect();
    }

    public function connect()
    {
        try {
            $this->dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($this->dsn, $this->name, $this->pass, $opt);
            # Connection succeeded, set the boolean to true.
            $this->bConnected = true;
        } catch (PDOException $e) {
            #Get the message with error
            die($e->getMessage());
        }
    }

    public function closeConnection()
    {
        # Set the PDO object to null to close the connection
        $this->pdo = null;
    }

    public function getOne($query, $id)
    {

        $ids = array($id);

            $this->connect();

        try {
            $row = $this->pdo->prepare($query);
            $row->execute($ids);
            $this->result = $row->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $e->getMessage();
            die();
        }

        $this->closeConnection();

        return $this->result;
    }

    public function getOneField($query)
    {
        self::connect();
        $rows = $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        self::closeConnection();
        return $rows;
    }

    public function getAll($query)
    {
        $this->connect();
        $result = $this->pdo->query($query);
        $this->result =$result->fetchAll();
        $this->closeConnection();
        return $this->result;
    }

    public function addField($query){
        var_dump($query);
        $this->connect();
        $this->pdo->query($query);
        $this->closeConnection();
    }
}




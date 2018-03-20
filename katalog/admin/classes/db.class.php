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

    public function __construct($host, $name, $pass, $db)
    {
        $this->host = $host;
        $this->name = $name;
        $this->pass = $pass;
        $this->database = $db;

        $this->connect();
    }

    function connect()
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
        $idArray = array($id);

        if (!$this->bConnected) {
            self::connect();
        }
        $pdo = $this->pdo;

        try {
            if (is_string($query)) {
                $row = $pdo->prepare($query);
                $this->result = $row->execute($idArray);
                $this->result = $row->fetchAll(PDO::FETCH_ASSOC);

            } else {
                $this->result = $query;
            }

        } catch (PDOException $e) {
            $e->getMessage();
            die();
        }
        self::closeConnection();
        return  $this->result;
    }


    public function getOneField($query)
    {
        if (!$this->bConnected) {
            self::connect();
            $rows = $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        }
        self::closeConnection();
    }


    public function getAll($query)
    {
        $ans = array();
        if (is_string($query)) {
            $this->query($query);

        } else {
            $this->result = $query;
        }

        if (!empty($this->result) && !$this->error) {
            while ($row = mysqli_fetch_array($this->result)) {
                $ans[] = $row;
            }
        }

        return $ans;
    }
}




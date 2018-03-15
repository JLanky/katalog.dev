<?php

class Db
{
    private $host;

    private $database;

    private $name;

    private $pass;

    public $result;

    private $link;

    public $query;

    public $error;

    public $param;

    public function __construct($host, $name, $pass, $db)
    {
        $this->host = $host;

        $this->name = $name;

        $this->pass = $pass;

        $this->database = $db;


        $this->connect();

    }

    public function connect()
    {
        $this->link = mysqli_connect($this->host, $this->name, $this->pass, $this->database) or die("Could not connect");

    }

    public function query($query)
    {
        $this->result = mysqli_query($this->link,$query );
        //var_dump($this->result);
        if (!$this->result) {
            print 'Query failed<br>' . mysqli_errno($this->error);
        }
        return $this->result;
    }

    public function getOne($query)
    {
        if (is_string($query)) {

            $this->query($query);

        } else {
            $this->result = $query;
        }

        if (!empty($this->result) && !$this->error) {
            $row = mysqli_fetch_array($this->result);

        } else {
            return false;
        }
        return $row;
    }

    public function getOneField($param, $query)
    {
        if (is_string($query)) {
            $this->query($query);

        } else {
            $this->result = $query;
        }
        $answer = array();
        if (!empty($this->result) && !$this->error) {

            while ($row = mysqli_fetch_array($this->result)) {
                if (array_key_exists($param, $row)) {
                    $answer[] = $row[$param];
                }
            }
        }
        return $answer;
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




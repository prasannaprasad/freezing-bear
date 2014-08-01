<?php

class MysqlConnector
{
    var $result;
    var $records;
    var $hostname;
    var $username;
    var $password;
    var $database;
    var $conn = NULL;

    public  function __construct($database, $username, $password, $hostname)
    {
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->hostname = $hostname;

        $this->conn    = mysqli_connect($this->hostname, $this->username, $this->password,$this->database);

        error_log("!!!" . mysqli_connect_error());
        if(mysqli_connect_error())
            throw new FreezingBearException("Unable to connect to DB",2014,__FILE__,__LINE__);
        return $this->conn;

    }


    public function getSingleRecord($query)
    {
        error_log("Executing in getSingleRecord: " . $query);
        $this->result = mysqli_query($this->conn,$query);
        if(!$this->result)
            throw new FreezingBearException("Unable to execute query",2016,__FILE__,__LINE__);
        return mysqli_fetch_assoc($this->result);
    }


}
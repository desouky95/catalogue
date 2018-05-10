<?php
class Database{
    public  $host="localhost";
    public  $dbuser="root";
    public  $password="";
    public  $dbname="catalogue";

    function __construct(){
        $this->password = "";
        $this->host = "localhost";
        $this->dbuser = "root";
        $this->dbname = "catalogue";
    }
    function connect(){
        $conn = mysqli_connect($this->host,$this->dbuser,$this->password,$this->dbname);
        if(mysqli_connect_errno()){
            die("connection Failed! " . mysqli_connect_error());
        }
        return $conn;
    }
    function query($conn,$sql){
        $res=mysqli_query($conn,$sql);
        if(!($res)){

            die("QUERY FAILED");
        }
        return $res;
    }
}
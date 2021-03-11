<?php

class User{

    private $db;

    public function __construct(){
        $this->db=new Database;

    }

    //Returns every row from database
    public function GetAllUsers(){
    $sql='SELECT * FROM DATABASE';
    $this->db->query($sql);
    $result=$this->db->resultSet();
    return $result;

    }

}
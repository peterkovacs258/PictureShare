<?php
    class UsersModel {
        private $db;


        public function __construct() {
            $this->db = new Database;

        }

    //Finds all user in our database, returns them in array
    public function ListAllUser()
    {
        $this->db->query("SELECT * FROM users");
        $users=$this->db->resultSet();
        if($this->db->rowcount()<1)
        {
            return false;
        }
        else
        {
            return $users;
        }


    }

    //Returns the smallest available id in the users table
    public function getSmallestAvailableID(){
        $sql="SELECT DISTINCT id +1 as newid FROM users WHERE id + 1 NOT IN (SELECT DISTINCT id FROM users) LIMIT 1";
         $this->db->query($sql);
         $smallestid=$this->db->single();
        return $smallestid->newid;
    }



    //Finds user by email address
    public function findUserByEmail($email){
        $sql="SELECT * FROM users WHERE email=:email";
        $this->db->query($sql);
        $this->db->bind(':email',$email);
        if($this->db->rowCount()<0)
        {
            return true;
        }else {return false;}

    }

    //Register new user
    public function registerUser($data)
    {
        $sql="INSERT INTO users(name, email,password) VALUES(:name, :email, :password)";
        $this->db->query($sql);
        $this->db->bind(':name',$data['userName']);
        $this->db->bind(':email',$data['email']);
        $this->db->bind(':password',$data['password']);

        //EXECUTE
        if($this->db->execute())
        {

            return true;

    }
    else{
        return false;
    }

    
    }
    //Login
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');

        //Bind value
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if($row!="")
        {
        $hashedPassword = $row->password;

        if (password_verify($password, $hashedPassword)) {
            return $row;
        } else {
            return false;
        }
    }
    else {
        return false;
    }
}

//Returns the filename of the profilepicture
public function getProfilePic($uid){
    $sql=('SELECT profilePic FROM users WHERE id=:uid');
    $this->db->query($sql);
    $this->db->bind(':uid',$uid);
    $res=$this->db->single();
    if(empty($res->profilePic))
    {
        return false;
    }
    else
    {
        return $res->profilePic;
    }
}
    


}



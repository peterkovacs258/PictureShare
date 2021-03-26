<?php
    class PicturesModel {
        private $db;


        public function __construct() {
            $this->db = new Database;

        }

    //Finds all pictures in our database, returns limited amount of them, in a certain page them in array
    public function getAllPicturesWithStartAndLimit($start,$limit)
    {
        $this->db->query("SELECT * FROM pictures ORDER BY id DESC LIMIT $start,$limit");
        $pics=$this->db->resultSet();
        if($this->db->rowcount()<1)
        {
            return false;
        }
        else
        {
            return $pics;
        }


    }
    //Returns hall of fame pictures
    public function getAllHallOfFamePictures($likelimit)
    {
        $sql="SELECT pictures.id,pictures.filename,pictures.title,likes.likeordislike, count(*) as'allLikes' FROM likes,pictures  WHERE likes.pictureID=pictures.id AND likes.likeordislike='like' group by likes.pictureID HAVING count(*) >= :likelimit";
        $this->db->query($sql);
        $this->db->bind(':likelimit',$likelimit);
        $res=$this->db->resultSet();
        if(!empty($res))
        {
          return $res;
        }
        else return false;
    }

    //Returns all the uploaded pictures of a specific user
  public function  getAllPicturesOfUser($uid)
    {
        $this->db->query("SELECT * FROM pictures WHERE userid=:uid");
        $this->db->bind(':uid',$uid);
        $pics=$this->db->resultSet();
        if($this->db->rowcount()<1)
        {
            return false;
        }
        else
        {
            return $pics;
        }

    }

    //Returns the smallest available id in the users table
    public function getSmallestAvailableID(){
        $sql="SELECT DISTINCT id +1 as newid FROM pictures WHERE id + 1 NOT IN (SELECT DISTINCT id FROM pictures) LIMIT 1";
         $this->db->query($sql);
         $smallestid=$this->db->single();
        return $smallestid->newid;
    }


    //Uploads picture to database, needs $data array's uid,picname,title
    public function uploadPicToDatabase($data)
    {
        $sql="INSERT INTO pictures (userid,categoryid,filename,title) VALUES(:uid,:cid,:fname,:title)";
        $this->db->query($sql);
        $this->db->bind(':uid',$data['uid']);
        $this->db->bind(':cid','8');
        $this->db->bind(':fname',$data['picname']);
        $this->db->bind(':title',$data['title']);

        if($this->db->execute())
        return true;
        else return false;
    }
    //Visszaadja a like objektumot egy adott képhez, és userhez, ha az létezik másképp false
    public function IsLikedByUser($picId,$uid)
    {
        $sql="SELECT * FROM likes WHERE pictureID=:picID AND userID=:uid";
        $this->db->query($sql);
        $this->db->bind(':picID',$picId);
        $this->db->bind(':uid',$uid);
        $res=$this->db->single();
        if(empty($res))
        {
            return false;
        }
        else return $res;
    }


    //RETURNS THE NUMBER OF ALL THE PICTURES IN THE DATABASE
    public function getNumberOfAllPictures(){
        $this->db->query("SELECT * FROM pictures");
        $pics=$this->db->resultSet();
       return $this->db->rowcount();
    }

    //KÉP törlése, az összes hozzátartozott like/dislike al

    public function deletePic($picid)
    {
        if($this->deleteAllLikes($picid))
        {
            $sql="DELETE FROM pictures WHERE id=:picid";
            $this->db->query($sql);
            $this->db->bind(":picid",$picid);
            if($this->db->execute())
            {
                return true;
            }
            else {return false;}
        }
    }
    //DELETES ALL THE LIKES OF A CERTAIN PICTURE
    public function deleteAllLikes($picid)
    {
        $sql="DELETE FROM likes WHERE pictureID=:picid";
        $this->db->query($sql);
        $this->db->bind(":picid",$picid);
        if($this->db->execute())
        {
            return true;
        }
        else return false;
    }



}



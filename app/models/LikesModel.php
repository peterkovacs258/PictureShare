<?php
    class LikesModel {
        private $db;


        public function __construct() {
            $this->db = new Database;

        }

        //Gets all likes and dislikes of one picture
        public function getAllLikes($picId)
        {
            $sql="SELECT * FROM likes WHERE pictureID=:picid";
            $this->db->query($sql);
            $this->db->bind(':picid',$picId);
            
                $res=$this->db->resultSet();
              if(count($res)>0)
              {
                  return $res;
              }
              else return false;
        
        }
        

        //inserts a like
        public function sendLike($data){
                $sql="INSERT INTO likes(pictureID,userID,likeordislike) VALUES(:picid,:uid,:type)";
                $this->db->query($sql);
                $this->db->bind(':picid',$data['picId']);
                $this->db->bind(':uid',$data['uid']);
                $this->db->bind(':type',$data['type']);

                if($this->db->execute())
                {
                    return true;
                }
                else return false;

                
        }

    //Returns the smallest available id in the users table
    public function getSmallestAvailableID(){
        $sql="SELECT DISTINCT id +1 as newid FROM likes WHERE id + 1 NOT IN (SELECT DISTINCT id FROM likes) LIMIT 1";
         $this->db->query($sql);
         $smallestid=$this->db->single();
        return $smallestid->newid;
    }

    //Returns the number of likes received by 1 user
    public function getAllLikesReceivedByUser($uid)
    {
        $sql="SELECT count(*) as allLikes FROM likes, pictures WHERE likes.pictureID=pictures.id AND pictures.userid=:uid AND likes.likeordislike='like'";
        $this->db->query($sql);
        $this->db->bind(':uid',$uid);
        $res=$this->db->single();
        if(empty($res))
        {
            return false;
        }
        else return $res->allLikes;
     }
    //Returns the number of dislikes received by 1 user
    public function getAllDisLikesReceivedByUser($uid){
            $sql="SELECT count(*) as allDisLikes FROM likes, pictures WHERE likes.pictureID=pictures.id AND pictures.userid=:uid AND likes.likeordislike='dislike'";
            $this->db->query($sql);
            $this->db->bind(':uid',$uid);
            $res=$this->db->single();
            if(empty($res))
            {
                return false;
            }
            else return $res->allDisLikes;
      }

    // Returns the number of likes given by 1 user 
     public function getAllLikesByUser($uid)
     {
        $sql="SELECT count(*) as allLikes FROM likes WHERE userID=:uid AND likeordislike='like'";
        $this->db->query($sql);
        $this->db->bind(':uid',$uid);
        $res=$this->db->single();
        if(empty($res))
        {return false;}
        else
        {return $res->allLikes;}

     }
      // Returns the number of likes given by 1 user 
      public function getAllDisLikesByUser($uid)
      {
         $sql="SELECT count(*) as allDisLikes FROM likes WHERE userID=:uid AND likeordislike='dislike'";
         $this->db->query($sql);
         $this->db->bind(':uid',$uid);
         $res=$this->db->single();
         if(empty($res))
         {return false;}
         else
         {return $res->allDisLikes;}
 
      }

        

}

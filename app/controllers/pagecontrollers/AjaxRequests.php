<?php

class AjaxRequests extends Controller {
    public function __construct() {
        $this->likeModel = $this->model('LikesModel');
        $this->pictureModel=$this->model('PicturesModel');
    }


    public function likes()
    {
        if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_POST['picid'])&&isset($_POST['typeoflike']))
        {
            $data=[
                'uid'=>$_SESSION['user_id'],
                'picId'=>$_POST['picid'],
                'type'=>$_POST['typeoflike']
            ];
           
            if($this->likeModel->sendLike($data))
            {
                if($data['type']=='like')
                {
                    echo "<div class='card-likebar-".$data['picId']."><a disabled class='btn btn-dark'><i class='far fa-thumbs-up'></i></a></div>"; 
                }
                else
                {
                    echo "<div class='card-likebar-".$data['picId']."><a disabled class='btn btn-dark'><i class='far fa-thumbs-down'></i></a></div>"; 

                }
            }
        }
    }

        //////////LOADS THE HALL OF FAME PICTURES IN THE CARDHOLDER\\\\\\\\\\\
    public function hallOfFame(){
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            //Returns the posts that reached a certain ammount of likes
            $pictures=$this->pictureModel->getAllHallOfFamePictures(8);
            if($pictures!=false)
            {
            $string="";
            
               
           $uid=$_SESSION['user_id'];
    
          //Végigmegyünk a képeken, és card elemekbe írjuk ki őket
        foreach($pictures as $pics){
    
            //Megnézzük hogy a jelenlegi user likeolta e már az adott képet
            $res=$this->pictureModel->IsLikedByUser($pics->id,$uid);
            
             //Összes like megjelenítése, like után///
                 $allLikesforthisPicture=$this->likeModel->getAllLikes($pics->id);
                 //Összes like/dislike száma
                 $numberOfLikes=0;
                 //Összes like
                    $likes=0;
                    //Összes dislike
                    $dislikes=0;
                        $numberOfLikes=count($allLikesforthisPicture);
                        foreach($allLikesforthisPicture as $currentlike)
                        {
                            if($currentlike->likeordislike=='like')
                            {$likes++;}
                            else
                            {$dislikes++;}
                        }
             $likespercent=$likes/$numberOfLikes*100;
             //Only continue if the image has more than 75% likes
             if($likespercent>74)  
             {         

             $likebarBottom="<div id='likebarbottom'><span><i class='far fa-thumbs-up'></i>".$likes."</span>".
             "<span><i class='far fa-thumbs-up'></i>".$dislikes."</span></div>";
    
    
            //Ha még nem likeolta megjelenítjük mindkét gombot
            if($res==false)
            {
                $likebar="<div class='card-likebar-".$pics->id."'><a  data-id=".$pics->id." data-name='like' class='btn-like btn btn-info'><i class='far fa-thumbs-up'></i></a>"
                ."<a data-id=".$pics->id." data-name='dislike' class='btn-like btn btn-danger'><i class='far fa-thumbs-down'></i></a></div>"; 
            }
            //Ha már likeolta, és a tpye='like' akkor csak a like gomb megjelenítve, disabled
            else if($res->likeordislike=='like')
            {
              $likebar=$likebarBottom;
    
            }
            //Ha már likeolta, és a tpye='dislike' akkor csak a dislike gomb megjelenítve, disabled
            elseif($res->likeordislike=='dislike')
            {
                $likebar=$likebarBottom;
    
            }
            ///LIKE\DISLIKE BAR
            $likePercent=0;
            $dislikePercent=0;
            if($numberOfLikes!=0)
            {
                $likePercent=$likes/$numberOfLikes*100;
                $dislikePercent=$dislikes/$numberOfLikes*100;
            }
            
           
    
            $mediabar="<div class='mediaBar'>
            <div id='likesbar' style='width:".$likePercent."%;height:5;background-color:green;'></div>
            <div id='dislikesbar' style='width:".$dislikePercent."%;height:5;background-color:red;'></div>".
            "</div>";
            //Css
                $string.='<div class="card">'
                . '<img width="400" src="'.URLROOT.'/public/img/'.$pics->filename.'" >'
                . '<div class="card-body">'
                .'<p>'.$pics->title.'</p>'
                .$mediabar
                .$likebar
                . '</div>'
                . '</div>';
            }
        }
    
            echo  $string;
        }
        else echo "false";
        }
    }


    //////////LOADS ALL THE PICTURES IN THE CARDHOLDER\\\\\\\\\\\
    public function loadAllPictures(){

        $pictures=$this->pictureModel->getAllPictures();
        if($pictures!=false)
        {
        $string="";
        
           
       $uid=$_SESSION['user_id'];

      //Végigmegyünk a képeken, és card elemekbe írjuk ki őket
    foreach($pictures as $pics){

        //Megnézzük hogy a jelenlegi user likeolta e már az adott képet
        $res=$this->pictureModel->IsLikedByUser($pics->id,$uid);

         //Összes like megjelenítése, like után///
             $allLikesforthisPicture=$this->likeModel->getAllLikes($pics->id);
             //Összes like/dislike száma
             $numberOfLikes=0;
             //Összes like
                $likes=0;
                //Összes dislike
                $dislikes=0;
                if($allLikesforthisPicture!=false)
                {
                    $numberOfLikes=count($allLikesforthisPicture);
                    foreach($allLikesforthisPicture as $currentlike)
                    {
                        if($currentlike->likeordislike=='like')
                        {$likes++;}
                        else
                        {$dislikes++;}
                    }
                }
                else
                {
                }
         $likebarBottom="<div id='likebarbottom'><span><i class='far fa-thumbs-up'></i>".$likes."</span>".
         "<span><i class='far fa-thumbs-up'></i>".$dislikes."</span></div>";


        //Ha még nem likeolta megjelenítjük mindkét gombot
        if($res==false)
        {
            $likebar="<div class='card-likebar-".$pics->id."'><a  data-id=".$pics->id." data-name='like' class='btn-like btn btn-info'><i class='far fa-thumbs-up'></i></a>"
            ."<a data-id=".$pics->id." data-name='dislike' class='btn-like btn btn-danger'><i class='far fa-thumbs-down'></i></a></div>"; 
        }
        //Ha már likeolta, és a tpye='like' akkor csak a like gomb megjelenítve, disabled
        else if($res->likeordislike=='like')
        {
          $likebar=$likebarBottom;

        }
        //Ha már likeolta, és a tpye='dislike' akkor csak a dislike gomb megjelenítve, disabled
        elseif($res->likeordislike=='dislike')
        {
            $likebar=$likebarBottom;

        }
        ///LIKE\DISLIKE BAR
        $likePercent=0;
        $dislikePercent=0;
        if($numberOfLikes!=0)
        {
            $likePercent=$likes/$numberOfLikes*100;
            $dislikePercent=$dislikes/$numberOfLikes*100;
        }
        
       

        $mediabar="<div class='mediaBar'>
        <div id='likesbar' style='width:".$likePercent."%;height:5;background-color:green;'></div>
        <div id='dislikesbar' style='width:".$dislikePercent."%;height:5;background-color:red;'></div>".
        "</div>";
        //Css
            $string.='<div class="card">'
            . '<img width="400" src="'.URLROOT.'/public/img/'.$pics->filename.'" >'
            . '<div class="card-body">'
            .'<p>'.$pics->title.'</p>'
            .$mediabar
            .$likebar
            . '</div>'
            . '</div>';
        }
        echo $string;
    }
    else return false;

    }



}

    ?>

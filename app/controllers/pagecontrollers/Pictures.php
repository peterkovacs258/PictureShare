<?php

class Pictures extends Controller {
    public function __construct() {
        $this->pictureModel = $this->model('PicturesModel');
        $this->likeModel = $this->model('LikesModel');

    }

    
    //Elindítja a hozzátartozó viewet, elküldi a képeket
    public function allPictures() {
        ///PAGE SELECTOS SETUP
        $limit=10;
        $start=0;
           ///Oldal tördelés
        if(isset($_GET['page']))
        {
        $page=$_GET['page'];
        if($page==""&&$page==1)
        {$start=0;}
        else {
           $start=($page*$limit)-$limit;
       }
        }
        //Megszámoljuk az összes kép számát, és elkezdjük a tördelést
       $count= $this->pictureModel->getNumberOfAllPictures();
       $pageselect=ceil($count/$limit);

       $pageselectorString="";
       if($pageselect>1)
       {
        for($i=1;$i<=$pageselect;$i++)
        {      $pageselectorString.= '<a class="pages" href="'.URLROOT.'/pictures/allPicture?page='.$i.'"><button class="btn btn-light">'.$i.'</button></a>';}
        }
         ////END OF PAGE SELECTORS
        $data['cards'] = $this->getAllPicturesInCards($start,$limit);
        $data['pageselect'] = $pageselectorString; 
 
         $this->view('allPictures',$data);
        
    }

    //Visszaadja bootstrap kártya elemekben a létező képeket
    public function getAllPicturesInCards($start,$limit){
    
        $pictures=$this->pictureModel->getAllPicturesWithStartAndLimit($start,$limit);
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

            //CHECKS FOR DELETE RULE
        if($this->deleteRuleReached($pics->id,$numberOfLikes,$likes,$dislikes)==false)
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
    return $string;
    }
    else return false;

    }

    ///////////Feltölt egy képet az adott felhasználóhoz\\\\\\\\\\\
    public function uploadPic()
    {
        $data=[
            'title'=>'',
            'picname'=>'',
            'uid'=>$_SESSION['user_id'],
            'error'=>''

        ];

        if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_FILES['filepic'])&&isset($_POST['title']))
        {
        $data['title']=trim($_POST['title']);
        $file = $_FILES['filepic'];
        $allowedImageTypes = array("image/png", "image/jpg", "image/jpeg", "image/bmp");

        $fileAccess=dirname(APPROOT).'/public/img/';
        $fileName = $file['name'];
        $tmpName = $file['tmp_name'];
        $fileType = $file['type'];

        if (in_array($fileType, $allowedImageTypes)) {
            $data['picname']=$fileName;
            if(file_exists($fileAccess.$fileName))
            {
            $fileName=time().'_'.$fileName;   
            $data['picname']=$fileName;
            } 
            if($this->pictureModel->uploadPicToDatabase($data))
            {
                move_uploaded_file($tmpName, $fileAccess.$fileName);
                echo'siker';
            }
            else echo 'nem siker';

        }
        else{$data['error']='Not an image';}
    }
    }

    
    ///RULE: KÉP TÖRLÉSE ADOTT DISLIKE MENNYISÉG UTÁN
    //JELENLEG AKKOR TÖRÖL HA TÖBB MINT 15 ÉRTÉKELÉS ÉRKEZETT,
    //ÉS AZ ÉRTÉKELÉSEK 70% A DISLIKE
    public function deleteRuleReached($picid,$numberOfLikes, $dislikes)
    {
        //Ha a lájkok száma több mint 10
        if($numberOfLikes>10)
        {
            $likespercent=$dislikes/$numberOfLikes*100;
            //És azok legalább 70% ban dislike ok
            if($likespercent<69)
            {
                //Törölje az adott képet
                $this->pictureModel->deletePic($picid);
            }
        }

    }

}

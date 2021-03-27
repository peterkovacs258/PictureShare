 <?php
 class Users extends Controller{

    public function __construct()
    {
        $this->userModel = $this->model('UsersModel');
        $this->pictureModel = $this->model('PicturesModel');
        $this->likeModel = $this->model('LikesModel');


    }


                                ///////FUNCTION\\\\\\
    //Adds a User with the name added
    public function addUser(){
        $data = [
            'id' => '',
            'email' => '',
            'userName' => '',
            'password' => '',
            'passwordConfirm' => '',
            'emailError' => '',
            'passwordError' => '',
            'passwordConfirmError' => '',
            'userNameError' => ''
        ];
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $data = [
                'id' => '',
                'email' => trim($_POST['email']),
                'userName' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'passwordConfirm' => trim($_POST['passwordC']),
                'emailError' => '',
                'passwordError' => '',
                'passwordConfirmError' => '',
                'userNameError' => ''
            ];
            //Name validator, only letters and numbers
            $nameValidator="/^[a-zA-Z0-9]*$/";
            if(empty($_POST['userName']))
            {
                $data['userNameError']="Empty field";
            }
            else if(!preg_match($nameValidator,$data['userName']))
            {
                $data['userNameError']="Username only can contain letters and numbers";
            }

            //Validate email
            if(empty($data['email']))
            {
                $data['emailError']='Please enter an email address';
            }
            elseif(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
            {
                $data['emailError']="Not an eail";
            }else
            {
                //Check for existing email address
                if($this->userModel->findUserByEmail($data['email']))
                {
                    $data['emailError']="Email address already exists";
                }
            }

             //Validate password
              $pwdValidator = "/^.*(?=.{5,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&.,']).*$/";
             if(empty($data['password']))
             {
                 $data['passwordError']="Empty field";
             }
             else if(!preg_match($pwdValidator,$data['password']))
             {
                 $data['passwordError']="Password must contain at least 5 character, one number,".
                 "one uppercase, one special character";
             }

             //validate password confirm
             if(empty($data['passwordConfirm']))
             {
                 $data['passwordConfirmError']="Empty field";
             }
             else if($data['passwordConfirm']!=$data['password'])
             {
                 $data['passwordError']="The confirmation must be the same as the password.";
             }
             //Make sure that errors are empty
             if(empty($data['usernameError'])&&
             empty($data['emailError'])&&
             empty($data['passwordError'])&&
             empty($data['passwordConfirmError']))
             {
                 //pwd hash
                 $data['password']=password_hash($data['password'],PASSWORD_DEFAULT);

                 //Register user from model
                 if($this->userModel->registerUser($data))
                 {
                 //redirect to the login page

                 header('location: '.URLROOT.'/main/index');
                 }
                 else 
                 {
                     die('Something went wrong');
                 }

             }
             else
             {echo "baj van tesó";}


        }
    }

                                ///////FUNCTION\\\\\\
       //Checks if user exists, logs him/her in
    public function login() {
        $data = [
            'title' => 'Login page',
            'email' => '',
            'password' => '',
            'usernameError' => '',
            'passwordError' => ''
        ];

        //Check for post
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Sanitize post data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'emailError' => '',
                'passwordError' => '',
            ];
            //Validate username
            if (empty($data['email'])) {
                $data['emailError'] = 'Please enter an email.';
            }

            //Validate password
            if (empty($data['password'])) {
                $data['passwordError'] = 'Please enter a password.';
            }

            //Check if all errors are empty
            if (empty($data['emailError']) && empty($data['passwordError'])) {
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['passwordError'] = 'Password or email is incorrect. Please try again.';

                   // $this->view("index",$data);
                }
            }

        } else {
            $data = [
                'email' => '',
                'password' => '',
                'emailError' => '',
                'passwordError' => ''
            ];
        }
       //$this->view("index",$data);
    }

                                ///////FUNCTION\\\\\\
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->name;
        $_SESSION['email'] = $user->email;
        echo "bent vagy";
        header('location: '.URLROOT.'/main/mainMenu');
    }



                            ///////FUNCTION\\\\\\
    //Loads the current users profile page with the profile informations
    public function profile()
    {
        //The current folder which contains all the profile pictures
        $imgsrcfolder=URLROOT.'/public/img/profilepics/';
        //Data i want to send to the view
        $data=[
            'imgsrc'=>$imgsrcfolder.'noimage.png',
            'email'=>'email',
            'username'=>'un',
            'uploads'=>0,
            'likesReceived'=>0,
            'dislikesReceived'=>0,
            'likesGiven'=>0,
            'dislikesGiven'=>0,
            'mypictures'=>''
        ];
        //Getting the current users id/email/name from session
        $uid=$_SESSION['user_id'];
        $data['email']=$_SESSION['username'];
        $data['username']=$_SESSION['email'];
        //Searching for existing profile pic
        $res=$this->userModel->getProfilePic($uid);
        if($res!=false)
        {
            $data['imgsrc']=$imgsrcfolder.$res;
        }
        //Checks for the number of all the uploads
        $res=$this->pictureModel->getAllPicturesOfUser($uid);
        if($res!=false)
        {
            $data['uploads']=count($res);
        }
        //Checks the number of likes received
        $res=$this->likeModel->getAllLikesReceivedByUser($uid);
        if($res!=false)
        {
            $data['likesReceived']=$res;
        }else
        {$data['likesReceived']=0;}
          //Checks the number of dislikes received
          $res=$this->likeModel->getAllDisLikesReceivedByUser($uid);
          if($res!=false)
          {
              $data['dislikesReceived']=$res;
          }else
          {$data['dislikesReceived']=0;}
       
          //Checks the number of likes given
          $res=$this->likeModel->getAllLikesByUser($uid);
          if($res!=false)
          {
            $data['likesGiven']=$res;
          }
          else{
            $data['likesGiven']=0;
          }
          //Checks the number of dislikes given
          $res=$this->likeModel->getAllDisLikesByUser($uid);
          if($res!=false)
          {
            $data['dislikesGiven']=$res;
          }
          else{
            $data['dislikesGiven']=0;
          }

          //returns all the uploads of the user
          $res=$this->pictureModel->getAllPicturesOfUser($uid);
          $uploadedPictures="";
          if($res!=false)
          {
              $uploadedPictures="<div class=' cardholder-profile'>";
              foreach($res as $pic)
              {
                  $uploadedPictures.="<div class='card'>".
                  '<img width="400" src="'.URLROOT.'/public/img/'.$pic->filename.'" >'.
                  "</div>";
              }
              $uploadedPictures.="</div>";
              $data['mypictures']=$uploadedPictures;
              
          }

        //Setting the current view
        $this->view("profilepage",$data);
    }

 }





?>
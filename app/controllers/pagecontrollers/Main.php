<?php

class Main extends Controller {
    public function __construct() {
    }

    //Index
    public function index() {
        $data = [
            'title' => 'Home page'
        ];
        
        if(isset($_SESSION['email']))
        {
         $this->view('mainMenu',$data);
        }
        else
        {
        $this->view('index', $data);
        }
    }
    //Sign-in
    public function signInPage()
    { 
        $data = [
            'title' => 'Sign-in'
        ];

        if(isset($_SESSION['email']))
        {
         $this->view('mainMenu',$data);
        }
        else
        {
        $this->view('signIn', $data);
        }
    }
    //Log-in
    public function logIn()
    {
        $data = [
            'title' => 'Sign-in'
        ];

        if(isset($_SESSION['email']))
        {
         $this->view('mainMenu',$data);
        }
        else
        {
        $this->view('index', $data);
        }
    }

    //Man menu
    public function mainMenu() {
        $data = [
            'title' => 'Main page'
        ];

    if(!isset($_SESSION['email']))
    {
     $this->view('index',$data);
    }
    else
    {
        $this->view('mainMenu', $data);
    }
    }

    //Logout
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        header('location:' . URLROOT . '/main/logIn');
    }

    //Upload picture
    public function uploadPage(){
        $data=[];
        if(empty($_SESSION['email']))
        {
            $this->view('index',$data);
        }
        else
        {
            $this->view('uploadPic',$data);
        }
    }



}

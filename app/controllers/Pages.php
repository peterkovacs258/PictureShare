<?php

class Pages extends Controller{
    public function __construct(){
        $this->userModel=$this->model('User');
    }

    public function index()
    {
        //Modell call
        $users=$this->userModel->getAllUsers();
        $this->view('pages/index');
    }
}
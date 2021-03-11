<?php
//LOAD THE MODEL AND THE VIEW
class Controller
{
    
    public function model($model)
    {
        require_once '../app/models/'.$model.'.php';
        return new $model();
    }

    //Load the view, checks if it exists
    public function view($view,$data=[])
    {
        if(file_exists('../app/views/'.$view.'.php'))
        {
            require_once('../app/views/'.$view.'.php');
        }
        else
        {
            die('view does not exists');
        }
    }

}

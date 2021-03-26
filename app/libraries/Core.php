<?php
  class Core {
    protected $currentController = 'Main';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){

      $url = $this->getUrl();

      // Checks the first part of the url for additions
      if(isset($url[0]))
      {
        if(file_exists('../app/controllers/pagecontrollers/' . ucwords($url[0]). '.php')){
          $this->currentController = ucwords($url[0]);
          if($this->currentController=='Users')
          {
            $this->currentMethod='logIn';
          }
          if($this->currentController=='Pictures')
          {
            $this->currentMethod='allPictures';
          }
          
          unset($url[0]);
        }
      }


      // Require the controller
      require_once '../app/controllers/pagecontrollers/'. $this->currentController . '.php';

      // Instantiate controller class
      $this->currentController = new $this->currentController;

      // Check for second part of url
      if(!empty($url[1])){
        // Check to see if method exists in controller
        if(method_exists($this->currentController, $url[1])){
          $this->currentMethod = $url[1];
          // Unset 1 index
          unset($url[1]);
        }
      }

      // Get params
      $this->params = $url ? array_values($url) : [];
      // Call a callback with array of params
      call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    //Returns the current url in the $url variable
    public function getUrl(){
      if(!empty($_GET['url'])){
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
      }
    }
  }



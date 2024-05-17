<?php

/**
 * @autores alf
 * @copyright 2020
 * @ver 1.1
 */

namespace app;
@session_start();
use classes\authentication\Authentication;
use classes\authentication\Users;


class ControllerLoginGoogle{

  private $urlLoginOk="https://" . _SITE . "/public/";                            /url a login sucesscifully
  private $urlLoginError="https://" . _SITE . "/public/autenticacao/erro";        /url a login error

  public function loginValidation(){
    
    $email = filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_STRING);
    $foto= filter_input(INPUT_POST, 'userImageURL', FILTER_SANITIZE_STRING);
    
    $p['email']=$email;
    //echo $email;
    $user= new Users("loginGoogle",$p);
    //print_r($user);
    $aut= new Authentication();
    if($user->results[0]['numElements']!=0){
    	//function setAuthentication($user, $nome, $email, $foto, $id, $level=1){
      $aut->setAuthentication($user->results[0]['id'], $user->results[0]['name'], $email, $foto, $user->results[0]['id'], $user->results[0]['type']);
      echo $this->urlLoginOk;
    }else {
      echo $this->urlLoginError;
    }   
  }
  
  
  public function getAutentication(){
    $aut=new Authentication();
    $aut->getAuthentication();
    echo $aut->webService();
  }
  
  public function logout(){
    $aut=new Authentication();
    $aut->logout();
  }
  
  
  public function error(){
    
  }
  
  
}
?>

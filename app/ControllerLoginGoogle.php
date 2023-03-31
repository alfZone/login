<?php

/**
 * @autores alf
 * @copyright 2020
 * @ver 1.0
 */

namespace app;
@session_start();
use classes\authentication\Authentication;
use classes\authentication\Users;


class ControllerLoginGoogle{

  public function validaLogin(){
    
    $email = filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_STRING);
    $foto= filter_input(INPUT_POST, 'userImageURL', FILTER_SANITIZE_STRING);
    
    $p['email']=$email;
    //echo $email;
    $user= new Users("loginGoogle",$p);
    //print_r($user);
    $aut= new Authentication();
    if($user->results[0]['numElements']!=0){
      $aut->setAuthentication($user->results[0]['id'], $user->results[0]['name'], $email, $foto, $user->results[0]['type']);
      echo "https://justicaepazviana.pt/public/admin/in";
    }else {
      echo  0;
    }   
  }
  
}
?>

<?php
 


require_once __DIR__ . '/../config.php';
 require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../autoload.php';
 use classes\authentication\Authentication;
use classes\authentication\Users;
 //use classes\members\Members;
   //echo "sdsdasd";
 
   
 $email = filter_input (INPUT_POST, 'userEmail', FILTER_SANITIZE_STRING);
 $foto= filter_input(INPUT_POST, 'userImageURL', FILTER_SANITIZE_STRING);
 $p['email'] = $email;
//echo "aqui: ";
//echo 'Erro'
 $user = new Users("loginGoogle",$p);
 $aut= new Authentication();
 //print_r ($user->results);

if($user->results[0]['numElements']!=0){
  $aut->setAuthentication($user->results[0]['username'],$user->results[0]['name'],$email,$foto, $user->results[0]['id'], $user->results[0]['usertype']); //($user, $name,$email, $level=1)
  echo $user->results[0]['usertype'];
}else {
  echo "Erro";
}


?>

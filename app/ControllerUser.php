<?php
namespace app;
@session_start();
//use classes\Database;
use classes\authentication\Authentication;
use classes\authentication\Users;



/**
 * @autores alf
 * @copyright 2021
 * @ver 1.5
 */


//require __DIR__ . '/../config.php';
//require __DIR__ . '/../bootstrap.php';


class ControllerUser{

 

  public function listOfUsers(){

    $pedidos=new Users("listOfUsers"); 
    echo $pedidos->webService();
 
   }
  
 public function contarUsers(){

   $pedidos=new Users("numberUserAtivos"); 
	 echo $pedidos->webService();

	}
  
 public function contarUsersPorTipo(){

   $pedidos=new Users("numberUserAtivosPorTipo"); 
	 echo $pedidos->webService();

	}
  
  public function contarAdmin(){

   $pedidos=new Users("numberAdmin"); 
	 echo $pedidos->webService();

	}
  
  public function getAutentication(){
    $aut=new Authentication();
    $aut->getAuthentication();
    //uncomment the next 3 lines for update google photo on database
    //$p['id']=$aut->getIdUser();
    //$p['photo']=$aut->getIdUser();
    //$update=new Users("photoUpdate",$p);
    echo $aut->webService();
 	}
  
}


?>

<?php
namespace classes\authentication;
@session_start();
//use classes\db\Database;

//https://console.cloud.google.com/apis/credentials?project=serene-circlet-163208
//https://docs.microsoft.com/pt-br/azure/active-directory/develop/v2-oauth2-auth-code-flow


/**
 * @author alf
 * @copyright 2019
 * @ver 4.2
 */
 
 
 
//echo "aqui";
class Authentication{
    
	  public $results;  
	   
    public function __construct(){
      //session_start();
      //print_r($_SESSION);
    }
    //Save session varibles for autentication
    //$aut->setAuthentication($user->results[0]['id'], $user->results[0]['nome'], $email, $foto, $user->results[0]['id'],$user->results[0]['tipo']);
    public function setAuthentication($user, $nome, $email, $foto, $id, $level=1){
      $_SESSION['user']=$user;
      $_SESSION['nome']=$nome;
      $_SESSION['level']=$level;
      $_SESSION['email']=$level;
      $_SESSION['foto']=$foto;
      $_SESSION['id']=$id;
    }
  
  //get session varibles for autentication
    public function getAuthentication(){
      if (isset($_SESSION['user']) ){
        $this->results[0]['user']=$_SESSION['user'];
        $this->results[0]['nome']=$_SESSION['nome'];
        $this->results[0]['level']=$_SESSION['level'];
        $this->results[0]['email']=$_SESSION['email'];
        $this->results[0]['foto']=$_SESSION['foto'];
        $this->results[0]['id']=$_SESSION['id'];
      }else{
        $this->results[0]['user']=null;
        $this->results[0]['nome']=null;
        $this->results[0]['level']=null;
        $this->results[0]['email']=null;
        $this->results[0]['foto']=null;
        $this->results[0]['id']=null;
      }
      
      return $this->results;
    }
  
  public function webService(){
    
    return json_encode($this->results, JSON_UNESCAPED_UNICODE);
    
  }
  
  //clean session varibles for autentication
    public function logout(){
      $_SESSION['user']=null;
      $_SESSION['nome']=null;
      $_SESSION['level']=null;
    }
    
  
    //get session varible user
    public function getUser(){
      return $_SESSION['user'];
    }

    //get session foto
    public function getPhoto(){
      return $_SESSION['foto'];
    }


    //get session varible user
    public function getIdUser(){
      return $_SESSION['id'];
    }

  //get session varible user
    public function getName(){
      return $_SESSION['nome'];
    }
  
  //get session varible user
    public function getLevel(){
      return $_SESSION['level'];
    }
   
  //verify if a usser is loged
  public function isLoged(){
    $resp=False;
    if (isset( $_SESSION['user'])){
      if ($_SESSION['user']!=""){
        $resp=True;
      }
    return $resp;
    }
  }
}

?>

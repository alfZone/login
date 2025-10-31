<?php

/**
 * @autores alf
 * @copyright 2025
 * @ver 2.0
 */

namespace app;
use src\Connection;
use PDO;

use classes\authentication\Authentication;
use classes\authentication\Users;
use classes\google\GoogleClient;

//require_once('../config.php');
require_once('../vendor/autoload.php');

class ControllerLoginGoogle{

    private $conn;
    private $database;

    public function __construct() {
        $this->database = new Connection();
        $this->conn = $this->database->getConnection();
    }

    // Obter um user por email
    public function getUserbyEmail($email, $json=true) {
        $p['email']=$email;
        $user = $this->database->getData("SELECT `id`, `name`, `email`,`type` FROM `authUser` WHERE `email`=:email and active=1",$p);
        if ($json){
          echo json_encode($user);
        }else{
          return $user;
        }
      }

    public function validaLogin(){
    
      //verifica os campos obrigatórios
      if (!isset($_POST['credential']) || !isset($_POST['g_csrf_token'])){
        header('location: ' . _CAMINHO_LOGIN . '?e=400');
        exit;
      }else{
        //apanhar o csrf
        $cookie = isset($_COOKIE['g_csrf_token']) ? $_COOKIE['g_csrf_token'] : '';
        //compara se a sessão é a mesma.
        if ($_POST['g_csrf_token'] !=$cookie){
          //se não for na mesma sessão volta para o login
          header('location: ' . _CAMINHO_LOGIN . '?e=401');
          exit;
        }else{
          //cria Cliente Google
          // Specify the CLIENT_ID of the app that accesses the backend
          $client = new GoogleClient(['client_id' => _GOOGLEID]);
          $payload = $client->verifyIdToken($_POST['credential']);
          
          //verifica se o payload tem email
          if (isset($payload['email'])) {
            //print_r($payload);
            //echo "<br>";
            $email = $payload['email'];
            $foto=  $payload['picture'];
            //$p['email']=$email;
            //$user= new Users("loginGoogle",$p);
            $user= $this->getUserbyEmail($email, false);
            //verifica se email existe na base de dados
            //echo "<br>Resultado da pesquisa na base de dados:<br>";
            //print_r(sizeof($user));
            //exit;
            
            if(sizeof($user)>0){
              $aut= new Authentication();
              
              $aut->setAuthentication($user[0]['id'], $user[0]['name'], $email, $foto, $user[0]['id'], $user[0]['type']);
              header("location: " . _CAMINHO_BACKEND);
              exit;
            }else {
              //o apy load não tem email
              //die('Problemas com a API da google');
              header('location: ' . _CAMINHO_LOGIN . '?e=401');
              exit;
            }   
          } else {
            //o payload não tem email
            // Invalid ID token
            //die('Problemas com a API da google');
            header('location: ' . _CAMINHO_LOGIN . '?e=401');
            exit;
          }
        }
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

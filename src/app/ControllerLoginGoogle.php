<?php

/**
 * @autores alf
 * @copyright 2024
 * @ver 1.0
 */

namespace app;

use classes\authentication\Authentication;
use classes\authentication\Users;
use classes\google\GoogleClient;

//require_once('../config.php');
require_once('../vendor/autoload.php');

class ControllerLoginGoogle{

    public function validaLogin(){
    
      //verifica os campos obrigatórios
      if (!isset($_POST['credential']) || !isset($_POST['g_csrf_token'])){
        header('location: ' . _CAMINHO_LOGIN . '?e=400');
        exit;
      }else{
        //apanhar o csrf
        $cookie= $_COOKIE['g_csrf_token'] ?? '';
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
            print_r($payload);
            //echo "<br>";
            $email = $payload['email'];
            $foto=  $payload['picture'];
            $p['email']=$email;
            $user= new Users("loginGoogle",$p);
            //verifica se email existe na base de dados
            if($user->results[0]['numElements']!=0){
              $aut= new Authentication();
              $aut->setAuthentication($user->results[0]['id'], $user->results[0]['name'], $email, $foto, $user->results[0]['id'], $user->results[0]['type']);
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

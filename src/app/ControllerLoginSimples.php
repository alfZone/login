<?php

/**
 * @autores alf
 * @copyright 2026
 * @ver 3.0
 */

namespace app;

use src\Connection;
use PDO;

use classes\authentication\Authentication;
use classes\authentication\Users;
use app\ControllerMail ;
//use classes\google\GoogleClient;

//require_once('../config.php');
//require_once('../vendor/autoload.php');

class ControllerLoginSimples
{

  private $conn;
  private $database;

  public function __construct()
  {
    $this->database = new Connection();
    $this->conn = $this->database->getConnection();
  }

  // Obter um user por email
  public function getUserByUserPass($user, $passw, $json = true)
  {
    $p['user'] = $user;
    $p['passw'] = md5($passw);
    print_r($p);
    $user = $this->database->getData("SELECT `id`, `nome`, `email`, `passw`, `gmail`, `ativo`, `adm` FROM `colUtilizador` 
                                  WHERE `ativo`=1 and  email=:user and passw=:passw", $p);
    if ($json) {
      echo json_encode($user);
    } else {
      return $user;
    }
  }


  // Obter um user por email
  public function getUserByUserCode($user, $code, $json = true)
  {
    $p['user'] = $user;
    $p['code'] = md5($code);
    //print_r($p);
    $user = $this->database->getData("SELECT `id`, `nome`, `email`, `passw`, `gmail`, `ativo`, `adm` FROM `colUtilizador` 
                                  WHERE `ativo`=1 and  email=:user and code=:code", $p);
    if ($json) {
      echo json_encode($user);
    } else {
      return $user;
    }
  }


  // Obter um user por email
  public function getUserbyEmail($email, $json = true)
  {
    $p['email'] = $email;
    $user = $this->database->getData("SELECT id, `nome`, `email`, `ativo` FROM `colUtilizador` WHERE email=:email and ativo=1", $p);
    //print_r($user);
    if ($json) {
      echo json_encode($user);
    } else {
      return $user;
    }
  }

  public function validaLogin()  {

    //verifica os campos obrigatórios
    //print_r($_REQUEST);
    if (!isset($_POST['user']) || !isset($_POST['passw'])) {
      header('location: ' . _PATH_TO_LOGIN . '?e=400');
      exit;
    } else {
      $user = $this->getUserByUserPass($_POST['user'], $_POST['passw'], false);
      //verifica se o user e pass existe na base de dados
      //echo "<br>Resultado da pesquisa na base de dados:<br>";
      //print_r($user);

      if ($user[0]['numElements'] > 0) {
        $aut = new Authentication();
        $aut->setAuthentication($user[0]['id'], $user[0]['nome'], $user[0]['email'], '', $user[0]['id'], $user[0]['adm']);
        header("location: " ._PATH_TO_BACKEND);
        exit;
      } else {
        //o apy load não tem email
        //die('Problemas com a API da google');
        header('location: ' ._PATH_TO_LOGIN . '?e=401');
        exit;
      }
    }
  }

  public function   sendRecoveryEmail($user) {
    //aqui devia enviar um email para o user com a password ou um link para criar nova password
    //gera um código de recuperação e guarda na base de dados
    $code = rand(100000, 999999);
    $p=['code' => md5($code), 'email' => $user];
    $userUpd = $this->database->getData("UPDATE `colUtilizador` SET `code`=:code WHERE `email`=:email", $p);
    //print_r($user);
    //envia um email para o user com o código de recuperação ou link para criar nova password
    $mail = new ControllerMail();
    $mail->sendMail($user, "Recuperação de Password", "Código de recuperação: " . $code  );
    //die();  
    
  }

  public function setEmailandCode($email,$code){
    $_SESSION['emailr']=$email;
    $_SESSION['coder']=$code;
  }

public function recoverLoginS2()  {

    //verifica os campos obrigatórios
    $input = json_decode(file_get_contents('php://input'), true);

    if($input){
      $_POST = $input;
    }
    //verifica os campos obrigatórios
    //print_r($_REQUEST);

    if (!(isset($_POST['email'])) or !(isset($_POST['code']))) {
      //header('location: ' . _PATH_TO_RECOVER_LOGIN . '?e=400');
      echo json_encode(["status" => "400", "message" => "O email e/ou o código não foram recebidos!"]);
      exit;
    } else {
      $user = $this->getUserByUserCode($_POST['email'],$_POST['code'], false);
      //verifica se o user e pass existe na base de dados
      //echo "<br>Resultado da pesquisa na base de dados:<br>";
      //print_r(sizeof($user));
      //print_r($user);

      if ($user[0]['numElements'] > 0) {
        //aqui devia enviar um email para o user com a password ou um link para criar nova password
        $this->setEmailandCode($_POST['email'],$_POST['code']);
        //header("location: " ._PATH_TO_RECOVER_CODE_FINAL . "?e=" . $user[0]['email']);
        echo json_encode(["status" => "200", "message" => "O código é valido!"]);
        exit;
      } else {
        //o apy load não tem email
        //die('Problemas com a API da google');
        //header('location: ' ._PATH_TO_RECOVER_CODE . '?e=404');
        echo json_encode(["status" => "404", "message" => "Problemas com a API do login simples"]);
        exit;
      }
    }
  } 

  public function recoverLogin()  {

    //verifica os campos obrigatórios
    $input = json_decode(file_get_contents('php://input'), true);

    if($input){
      $_POST = $input;
    }

    if (!isset($_POST['email'])) {
      echo json_encode(["status" => "400", "message" => "O email não foi encontrado"]);
      //header('location: ' . _PATH_TO_RECOVER_LOGIN . '?e=400');
      exit;
    } else {
      $user = $this->getUserbyEmail($_POST['email'], false);
      //verifica se o user e pass existe na base de dados
      //echo "<br>Resultado da pesquisa na base de dados:<br>";
      //print_r(sizeof($user));
      //print_r($user);

      if ($user[0]['numElements'] > 0) {
        //aqui devia enviar um email para o user com a password ou um link para criar nova password
        $this->sendRecoveryEmail($user[0]['email']);
        echo json_encode(["status" => "200", "message" => "O código foi enviado para o email!"]);
        //header("location: " ._PATH_TO_RECOVER_CODE . "?e=" . $user[0]['email']);
        exit;
      } else {
        //o apy load não tem email
        echo json_encode(["status" => "404", "message" => "Problemas com a API do login simples"]);
        //die('Problemas com a API da google');
        //header('location: ' ._PATH_TO_RECOVER_LOGIN . '?e=404');
        exit;
      }
    }
  } 

  public function updatePass() {
        //parse_str(file_get_contents("php://input"), $putData);
        $putData = json_decode(file_get_contents('php://input'), true);

        $p['passw']=md5($putData['Passw']);
        $p['email']=$_SESSION['emailr'];
        $p['code']=md5($_SESSION['coder']);
        $_SESSION['emailr']=null;
        $_SESSION['coder']=null;

      //print_r($p);
        $resp = $this->database->setData("UPDATE `colUtilizador` SET `passw`=:passw  WHERE `email`=:email and `code`=:code", $p);
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }

  public function getAutentication(){
    $aut = new Authentication();
    $aut->getAuthentication();
    echo $aut->webService();
  }

  public function logout(){
    $aut = new Authentication();
    $aut->logout();
  }


  public function error() {}
}

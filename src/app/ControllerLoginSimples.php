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
    $user = $this->database->getData("SELECT fctEmpresasCurso.`NIF` as id, fctEmpresasCurso.`NIF` as user, fctEmpresas.NomeEmpresa, fctEmpresas.Email 
                                      FROM `fctEmpresasCurso` inner join fctEmpresas on fctEmpresasCurso.NIF=fctEmpresas.NIF 
                                      WHERE fctEmpresasCurso.`NIF`=:user and passw=:passw", $p);
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
    $user = $this->database->getData("SELECT `Processo` as id, `Nome` as name, `Email` as email, `Telefone`, `DC` as type, `ativo` FROM `fctProfessores` WHERE email=:email and ativo=1", $p);
    if ($json) {
      echo json_encode($user);
    } else {
      return $user;
    }
  }

  public function validaLogin()
  {

    //header('location: ' . _CAMINHO_LOGIN . '?e=400');
    //exit;

    //verifica os campos obrigatórios
    //print_r($_REQUEST);

    if (!isset($_POST['user']) || !isset($_POST['passw'])) {
      header('location: ' . _CAMINHO_LOGIN . '?e=400');
      exit;
    } else {
      $user = $this->getUserByUserPass($_POST['user'], $_POST['passw'], false);
      //verifica se o user e pass existe na base de dados
      //echo "<br>Resultado da pesquisa na base de dados:<br>";
      //print_r(sizeof($user));
      //print_r($user);
      //exit;

      if ($user[0]['numElements'] > 0) {
        $aut = new Authentication();
        //setAuthentication($user, $name,$email, $foto, $id, $level=1)
        $aut->setAuthentication($user[0]['id'], $user[0]['NomeEmpresa'], $user[0]['Email'], '', $user[0]['id'], "empresa");
        header("location: " . _CAMINHO_BACKEND_EMPRESAS);
        exit;
      } else {
        //o apy load não tem email
        //die('Problemas com a API da google');
        header('location: ' . _CAMINHO_LOGIN . '?e=401');
        exit;
      }
    }
  }


  public function getAutentication()
  {
    $aut = new Authentication();
    $aut->getAuthentication();
    echo $aut->webService();
  }

  public function logout()
  {
    $aut = new Authentication();
    $aut->logout();
  }


  public function error() {}
}

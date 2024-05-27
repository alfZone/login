<?php

/**
 * @autores Alf
 * @copyright 2021
 * @ver 1.2
 */

namespace classes\authentication;
use classes\db\Database;
use classes\db\LayerDB;
//require __DIR__ . '/../config.php';
//require __DIR__ . '/../bootstrap.php';

ini_set("error_reporting", E_ALL);

//include_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
//include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/ClassDatabase.php";


class Users extends LayerDB{
 
  public $instrucaoSQL = array ("loginGoogle"=>'SELECT `id`, `email`, `name`, `type`  FROM `authUser` WHERE `email`=:email and active=1',
                                "numberUserAtivos" => 'SELECT count(`id`) as numero FROM `base_utilizadores` where `ativo`=1',
                                "numberUserAtivosPorTipo" => 'SELECT tipo, count(`id`) as numero FROM `base_utilizadores` where `ativo`=1 GROUP by tipo',
                                "numberAdmin" => 'SELECT count(`id`) as numero FROM `base_utilizadores` where `ativo`=1 and tipo=3',
                                "listOfUsers" => 'SELECT id, `nome` as name FROM base_utilizadores WHERE `ativo`=1 order by `nome`',
                                "photoUpdate" => 'UPDATE base_utilizadores SET `photo` = :photo WHERE `id` = :id AND (`photo` <> :photo)'
                                );
  
   
 
  
 public function doAction($accao, $parameters=""){
  
    switch ($accao){

      case "updUsers":
            $this->execQuery($accao, $parameters);
            break;
      case "numberUserAtivos":
      case "numberUserAtivosPorTipo":
      case "numberAdmin":
            $this->getQuery($accao, $parameters);
            break;
  
      default:
          $this->autoQuery($accao, $parameters);
          break;
    }

  } 
 
}

?>

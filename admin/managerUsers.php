
<?php
ini_set("error_reporting", E_ALL);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../autoload.php';
use classes\db\TableBD;

$table= new TableBD();
$table->setTemplate(_CAMINHO_MANUTENCAO . "tables.html");
$table->setTitulo("Utilizadores autorizados");
//SELECT `id`, `email`, `name`, `type`, `active`, `photo` FROM `authUser` WHERE 1

$table->prepareTable("authUser");

$table->setFieldsAtive("id,email, name,  active",'see');
$table->setFieldsAtive("email, name, type, active, photo", 'new');
$table->setFieldsAtive("email, name, type, active, photo", 'edit');
$table->setFieldsAtive("email, name, type, active, photo", 'csv');

//$table->setFieldList("codCategorie",1,"SELECT `codCategorie`, `categorie` FROM `cmsArticlesCategorie` ORDER by `categorie`");
$table->setFieldList("active",2,"0=>Desativo,1=>Ativo");
$table->setFieldList("type",2,"100=>Admin,1=>Editor");
$table->setLabel('id',"ID");
$table->setLabel('email',"Email");
$table->setLabel('name',"Nome do Utilizador");
$table->setLabel('active',"Ativo"); 
$table->setLabel('type',"Tipo");
$table->setLabel('photo',"Foto");
$table->showHTML();
?>

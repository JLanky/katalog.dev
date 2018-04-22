<?php
include "config.php";
if(!empty($_POST)){
$auth=new Authors($Db);
$auth->addAuthor($_POST);
}
require_once('templates/add_author.phtml');




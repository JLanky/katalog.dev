<?php
include "config.php";

if (!empty($_POST)) {

$genre = new Genres($Db);
$genre->addGenre($_POST);

}
require_once('templates/add_genre.phtml');



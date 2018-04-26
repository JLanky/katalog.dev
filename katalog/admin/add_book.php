<?php
include "config.php";
if ($_POST) {
    $book = new Books($Db);
    $book->addBook($_POST);
}
$authors = new Authors($Db);
$genres = new Genres($Db);

$authors = $authors->getAuthorsList($Db);
$genres = $genres->getGenresList($Db);
require_once('templates/add_book.phtml');
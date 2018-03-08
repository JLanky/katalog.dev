<?php
include "config.php";
if ($_POST) {
    $book = new Books($Db);
    $book->addBook($_POST);
}
$authors_array = Authors::getAllAuthors($Db);
$genres_array  = Genres::getAllGenres($Db);
require_once('templates/add_book.phtml');
?>


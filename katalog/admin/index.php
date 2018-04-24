<?php
include "config.php";
if (!empty($_GET['book_id'])) {
    $book_id = (int)$_GET['book_id'];
    $book = new Books($Db);
    $book->deleteBook($book_id);
}

$authorObject = new Authors( $Db );
$genreObject  = new Genres( $Db );
$authorsList = $authorObject->getAuthorsList( $Db );
$genresList  = $genreObject->getGenresList( $Db );

require_once('templates/index.phtml');
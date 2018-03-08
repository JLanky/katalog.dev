<?php
include "config.php";
if (!empty($_GET['book_id'])) {
    $book_id           = (int) $_GET['book_id'];
    $book = new Books($Db,$book_id);
    $book->deleteBook($book_id);
}
$array = Books::getAllBooks($Db);
require_once('templates/index.phtml');
?>


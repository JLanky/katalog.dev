<?php
include "config.php";
if (!empty($_GET['book_id'])) {
    $book_id = (int)$_GET['book_id'];
    $book = new Books($Db);
    $book->deleteBook($book_id);
}

require_once('templates/index.phtml');
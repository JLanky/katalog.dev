<?php
include "config.php";
if (isset($_GET['delete']) && !empty($_GET['book_id'])) {
    $book_id = (int)$_GET['book_id'];
    $book = new Books($Db);
    echo $book->deleteBook($book_id);
}
if (isset($_GET['edit']) && !empty($_GET['book_id'])) {
    $bookData= array();
    $book_id = (int)$_GET['book_id'];
    $book = new Books($Db);
    
    $bookData = $book->getBook($book_id);

    $bookData['authors']=  $book->getBookAuthors($book_id);
    $bookData['genres']=  $book->getBookGenres($book_id);

    echo json_encode($bookData, true);
}
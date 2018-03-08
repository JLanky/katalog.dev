<?php
include "config.php";
if (isset($_REQUEST['book_id'])) {
        $book_id = (int) $_REQUEST['book_id'];
        $book = new Books($Db,$book_id);
        $authors_array = Authors::getAllAuthors($Db);
        $genres_array  = Genres::getAllGenres($Db);

        if(isset($_POST['saveBookData'])) {
                $book->updateBook($_POST);
        }
} else {
        header('Location:index.php');
}
require_once('templates/update_books.phtml');
?>



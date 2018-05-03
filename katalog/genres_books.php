<?php
include "admin/config.php";
if (!empty($_GET['genre_id'])) {
    $genre_id = (int)$_GET['genre_id'];

    $genre = new Genres($Db, $genre_id);
    $books = $genre->getGenreBooks();
}
require_once('admin/templates/genres_books.phtml');



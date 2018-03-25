<?php
include "admin/config.php";
if (!empty($_GET['genre_id'])) {
    $genre_id = (int)$_GET['genre_id'];

    $genre = new Genres($Db, $genre_id);
    $books = $genre->getGenreBooks();
    print '<h2><strong style="margin: 0 auto">Список книг жанра ' . $genre->getGenre($genre_id) . '</strong></h2>';

    foreach ($books as $book) {
        print '<div style="text-align:center"><a href="form.php?book_id=' . $book['book_id'] . '">' . $book['title'] . '</a></div><br>';
    }
}
require_once('admin/templates/genres_books.phtml');
?>


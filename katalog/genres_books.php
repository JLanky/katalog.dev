<?php
include "admin/config.php";
if (!empty($_GET['genre_id'])) {
    $genre_id = (int) $_GET['genre_id'];
    $genre=new Genres($Db,$genre_id);
    $books=$genre->getCurrentGenreBooks();
    print '<h2><strong style="margin: 0 auto">Список книг жанра ' . $genre->genre . '</strong></h2>';

    for ($g = 0, $G = count($books); $g < $G; $g++) {
        print '<div style="text-align:center"><a href="form.php?book_id=' . $books[$g]->book_id . '">' . $books[$g]->name . '</a></div><br>';
    }

}
require_once('admin/templates/genres_books.phtml');
?>


<?php
include "config.php";
if (isset($_GET['genre_id']) && !empty($_GET['genre_id'])) {
    $genre_id = (int)$_GET['genre_id'];
    $genre = new Genres($Db, $genre_id);

    $genre->deleteGenre($genre_id);
}
$genres = Genres::getAllGenres($Db);
require_once('templates/delete_genre.phtml');

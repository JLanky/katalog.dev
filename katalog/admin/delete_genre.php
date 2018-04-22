<?php
include "config.php";
if (!empty($_GET['genre_id'])) {
    $genre_id = (int)$_GET['genre_id'];
    $genre = new Genres($Db);
    $genre->deleteGenre($genre_id);
}
$genres = Genres::getAllGenres($Db);
require_once('templates/delete_genre.phtml');

?>


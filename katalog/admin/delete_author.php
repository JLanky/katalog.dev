<?php
include "config.php";
if (!empty($_GET['author_id'])) {
    $author_id  = (int) $_GET['author_id'];
    $authors=new Authors($Db);
    $authors->deleteAuthor($author_id);
}
$authors = Authors::getAllAuthors($Db);
require_once('templates/delete_author.phtml');



<?php
include "config.php";
if (!empty($_GET['author_id'])) {
    $author_id  = (int) $_GET['author_id'];
    $auth=new Authors($Db);
    $auth->deleteAuthor($author_id);

}
$authors = Authors::getAllAuthors($Db);
require_once('templates/delete_author.phtml');
?>


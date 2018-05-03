<?php
include "admin/config.php";

if (!empty($_GET['author_id'])) {
    $author_id = (int)$_GET['author_id'];

    $authorObject = new Authors($Db);
    $authorObject->getAuthor($author_id);

    $books = $authorObject->getCurrentAuthorBooks();
}
require_once('admin/templates/authors_books.phtml');



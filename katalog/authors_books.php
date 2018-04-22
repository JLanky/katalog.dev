<?php
include "admin/config.php";

if (!empty($_GET['author_id'])) {
    $author_id = (int)$_GET['author_id'];

    $authorObject = new Authors($Db);
    $authorObject->getAuthor($author_id);

    $books = $authorObject->getCurrentAuthorBooks();

    echo '<h2><strong style="margin: 0 auto">Список книг автора ' . $authorObject->getAuthorName() . '</strong></h2>';
    foreach ($books as $book){
        echo '<div style="text-align:center">
<a href="form.php?book_id=' . $book['book_id'] . '">' .$book['title']  . '</a></div><br>';

    }


}
require_once('admin/templates/authors_books.phtml');
?>


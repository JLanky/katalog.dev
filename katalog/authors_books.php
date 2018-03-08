<?php
include "admin/config.php";
if (!empty($_GET['author_id'])) {
    $author_id = (int) $_GET['author_id'];
    $author=new Authors($Db,$author_id);
    $books=$author->getCurrentAuthorBooks();
    print '<h2><strong style="margin: 0 auto">Список книг автора ' . $author->author . '</strong></h2>';

    for ($g = 0, $G = count($books); $g < $G; $g++) {
        print '<div style="text-align:center"><a href="form.php?book_id=' . $books[$g]->book_id . '">' . $books[$g]->name . '</a></div><br>';
    }


}
require_once('admin/templates/authors_books.phtml');
?>


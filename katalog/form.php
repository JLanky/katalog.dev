<?php
include "admin/config.php";
if ($_POST) {
    $error = '';
    $message = '';
    $initials = trim($_POST["initials"]);
    $address = trim($_POST["address"]);
    $quantity = trim($_POST["quantity"]);
    $book_id = (int)$_POST['book_id'];
    $bookObject = new Books($Db);
    $book = $bookObject->getBook($book_id);

    if (is_numeric($initials) && !empty($initials)) {
        $error .= "В строке ФИО вводятся только буквы<br>";
    }
    if (!is_numeric($quantity) && !empty($quantity)) {
        $error .= "В строке количество вводятся только цифры<br>";
    }

    if ((strlen($initials) == 0)) {
        $error .= "Вы не заполнили поле ФИО<br>";
    }
    if ((strlen($address) == 0)) {
        $error .= "Вы не заполнили поле Адрес<br>";
    }
    if (empty($quantity)) {
        $error .= "Вы не заполнили поле Количество экземпляров";
    }
    if ($error != "") {
        $error = "<center class=\"t\">$error</center> ";
    } else {
        $message = 'Пользователь <b>' . $initials . '</b>,проживающий по адресу:<b>' . $address . '</b>
    хочет купить у вас <b>' . $quantity . '</b> экземпляров книги ' . $book['title'];
        if (mail("admin@catalog.com", "Новый заказ книги", $message)) {
            $message = '<h2><center>Данные успешно отправлены</center></h2>';
        }
    }
}

if (!empty($_REQUEST['book_id'])) {

    $book_id = (int)$_REQUEST['book_id'];
    $bookObject = new Books($Db);
    $book = $bookObject->getBook($book_id);
    $authors = $bookObject->getBookAuthors($book_id);
    $genres = $bookObject->getBookGenres($book_id);
    $authorsHTML = array();
    $genresHTML = array();
    foreach ($authors as $author) {
        $authorsHTML[] = '<a href="authors_books.php?author_id=' . $author['author_id'] . '">' . $author['author'] . '</a>';
    }

    foreach ($genres as $genre) {
        $genresHTML[] = '<a href="genres_books.php?genre_id=' . $genre['genre_id'] . '">' . $genre['genre'] . '</a> ';
    }
}
require_once('admin/templates/form.phtml');



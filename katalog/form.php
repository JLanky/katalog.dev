<?php
include "admin/config.php";
if ($_POST) {
    $error = "";
    $initials = trim($_POST["initials"]);
    $address = trim($_POST["address"]);
    $quantity = trim($_POST["quantity"]);

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
        print "<center class=\"t\">$error</center> ";
    } else {
        $message = 'Пользователь <b>' . $initials . '</b>,проживающий по адресу:<b>' . $address . '</b>
    хочет купить у вас <b>' . $quantity . '</b> экземпляров книги ' . $book_array['name'] . '';
        if (mail("admin@mail.ru", "Новый заказ книги", $message)) {
            print '<h2><center>Данные успешно отправлены</center></h2>';
        }
    }
}

if (!empty($_REQUEST['book_id'])) {
    $book_id = (int)$_REQUEST['book_id'];
    $book = new Books($Db, $book_id);

    $authors = '';
    $genres = '';
    $auth = $book->authors;
    $genr = $book->genres;
    for ($i = 0, $I = count($auth); $i < $I; $i++) {

        if (!empty($authors)) {
            $authors .= ',';
        }
        $authors .= '<a href="authors_books.php?author_id=' . $auth[$i]->id . '">' . $auth[$i]->author . '</a> ';
    }


    for ($i = 0; $i < count($genr); $i++) {

        if (!empty($genres)) {
            $genres .= ',';
        }

        $genres .= '<a href="genres_books.php?genre_id=' . $genr[$i]->id . '">' . $genr[$i]->genre . '</a> ';
    }


} else {
    print 'Произошла ошибка';
}
require_once('admin/templates/form.phtml');



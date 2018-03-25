<?php
include "admin/config.php";
$book = new Books($Db);
$book = $book->getAllBooks();

require_once('admin/templates/user.phtml');

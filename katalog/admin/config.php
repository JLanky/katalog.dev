<?php
include "classes/db.class.php";
include "classes/books.class.php";
include "classes/authors.class.php";
include "classes/genres.class.php";

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','root');
define('DB_NAME','test');

$Db = new Db(DB_HOST,DB_USER,DB_PASS,DB_NAME);


<?php
include "admin/config.php";
$array=Books::getAllBooks($Db);
require_once('admin/templates/user.phtml');

<?php
session_start();
define("UPLOAD_DIR", "./upload/");
define("IN_APP", true);
require_once("db/database.php");
require_once("utils/functions.php");
$dbh = new DatabaseHelper("localhost", "root", "", "cafeteria", 3306);
?>
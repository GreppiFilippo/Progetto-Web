<?php
// Prevent direct access
if (php_sapi_name() !== 'cli' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
	http_response_code(403);
	exit('Access denied');
}

session_start();
define("UPLOAD_DIR", "./upload/");
define("IN_APP", true);
require_once("db/database.php");
require_once("utils/functions.php");
$dbh = new DatabaseHelper("localhost", "root", "", "cafeteria", 3306);
?>
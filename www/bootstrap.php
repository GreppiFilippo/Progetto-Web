<?php
// Prevent direct access
if (php_sapi_name() !== 'cli' && isset($_SERVER['SCRIPT_FILENAME']) && is_string($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
    http_response_code(403);
    header('Location: not-authorized.php');
    exit;
}

session_start();
define("UPLOAD_DIR", "./upload/");
define("IN_APP", true);
require_once("db/database.php");
require_once("utils/functions.php");
$dbh = new DatabaseHelper("localhost", "root", "", "cafeteria", 3306);
?>
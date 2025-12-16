<?php
    require_once 'bootstrap.php';
    $_SESSION = [];
    session_destroy();
    header("Location: index.php");
    exit;
?>
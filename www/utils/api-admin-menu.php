<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once "../bootstrap.php";
    require_once "../utils/functions.php";
    $category="all";
    $state="all";
    $name="";
    if(isset($_GET['category']))
        $category = $_GET['category'];
    if(isset($_GET['state']))
        $state = $_GET['state'];
    if(isset($_GET['name']))
        $name = $_GET['name'];
    $dishes = $dbh->getFilteredDishes($category, $state, $name);
    header('Content-Type: application/json');
    echo json_encode($dishes);
?>
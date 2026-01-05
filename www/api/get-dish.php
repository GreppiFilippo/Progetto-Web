<?php
    require_once "../bootstrap.php";
    if (isset($_GET["id"])) {
        $dishData = $dbh->getDishFromId($_GET["id"]);
        $dishData["image"]= UPLOAD_DIR . $dishData["image"];
    }
    header('Content-Type: application/json');
    echo json_encode($dishData);
?>
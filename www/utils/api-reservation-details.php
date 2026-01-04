<?php
    require_once "../bootstrap.php";
    if(isset($_GET['reservation_id'])) {
        $reservation_id = (int)$_GET['reservation_id'];
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "reservation_id parameter is required"]);
        exit;
    }
    $response = $dbh->getReservationItemsDetailed($_GET["reservation_id"]);
    header('Content-Type: application/json');
    echo json_encode($response);
?>
<?php
    require_once "../bootstrap.php";
    if(isset($_POST['reservation_id']) && isset($_POST['user_id'])) {
        $reservation_id = (int)$_POST['reservation_id'];
        $user_id = (int)$_POST['user_id'];
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "reservation_id and user_id parameters are required"]);
        exit;
    }
    $result = $dbh->deleteReservation($reservation_id, $user_id);
    header('Content-Type: application/json');
    echo json_encode(["success" => $result]);
?>
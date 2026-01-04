<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once "../bootstrap.php";
    require_once "../utils/functions.php";
    list($response["bookings"], $totalItems) = $dbh->getFilteredReservations("", "", "all", "", 0, 6);
    for ($i = 0; $i < count($response["bookings"]); $i++) {
        $response["bookings"][$i]["badge"] = bookingStatusBadge($response["bookings"][$i]["status"]);
    }
    $response["bookings_count"] = $dbh->todayBookingsCount();
    $response["users_count"] = $dbh->countUsers();
    $response["earnings_today"] = $dbh->todayEarnings();
    //$response["active_dishes"] = $dbh->countDishesToday();
    $response["top_dishes"] = $dbh->getTopDishes(3);
    header('Content-Type: application/json');
    echo json_encode($response);
?>
<?php
    ini_set('display_errors', 1);   // mostra gli errori a video
    ini_set('display_startup_errors', 1); // mostra errori di avvio
    error_reporting(E_ALL);  
    require_once "../bootstrap.php";

    $date = isset($_GET['date']) ? $_GET['date'] : "";
    $hour = isset($_GET['hour']) ? $_GET['hour'] : "";
    $state = isset($_GET['state']) ? $_GET['state'] : "all";
    $name = isset($_GET['name']) ? $_GET['name'] : "";
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 4; 
    $offset = ($page - 1) * $perPage;
     
    list($response["bookings"], $totalItems) = $dbh->getFilteredReservations($date, $hour, $state, $name, $offset, $perPage);
    for ($i = 0; $i < count($response["bookings"]); $i++) {
        $response["bookings"][$i]["badge"] = bookingStatusBadge($response["bookings"][$i]["status"]);
    }

    // assicurati che totalItems sia un numero intero
    $totalItems = is_numeric($totalItems) ? (int)$totalItems : 0;

    // calcola numero di pagine
    $totalPages = $perPage > 0 ? ceil($totalItems / $perPage) : 0;

    $stats = $dbh->getReservationStats();
    $response["today_bookings"] = $dbh->todayBookingsCount();
    $response["completed"] = $stats["completed"];
    $response["preparing"] = $stats["preparing"];
    $response["ready"] = $stats["ready"];
    header('Content-Type: application/json');
    echo json_encode([
        "data" => $response,
        "totalPages" => $totalPages
    ]);
?>
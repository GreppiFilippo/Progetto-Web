<?php
require_once '../bootstrap.php';

header('Content-Type: application/json');

if (!isUserLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Non autorizzato']);
    exit();
}

$userId = (int) $_SESSION["user_id"];

if (isset($_GET['reservation_id'])) {
    $reservationId = (int) $_GET['reservation_id'];
    $reservation = $dbh->getReservationById($reservationId, $userId);

    if (!$reservation) {
        http_response_code(404);
        echo json_encode(['error' => 'Prenotazione non trovata']);
        exit();
    }

    echo json_encode([
        'status' => $reservation['status'],
        'badgeClass' => reservationBadgeClass($reservation['status']),
        'canCancel' => canCancelReservation($reservation['status'])
    ]);
} else {
    $counts = $dbh->getReservationCountsByUser($userId);
    $showAll = isset($_GET["show_all"]) && $_GET["show_all"] == "1";
    $limit = $showAll ? null : 3;
    $reservations = $dbh->getReservationsByUser($userId, $limit);

    $statusData = [];
    foreach ($reservations as $reservation) {
        $statusData[] = [
            'id' => $reservation['reservation_id'],
            'status' => $reservation['status'],
            'badgeClass' => reservationBadgeClass($reservation['status']),
            'canCancel' => canCancelReservation($reservation['status'])
        ];
    }

    echo json_encode([
        'active_count' => (int) ($counts['active_count'] ?? 0),
        'completed_count' => (int) ($counts['completed_count'] ?? 0),
        'reservations' => $statusData
    ]);
}
?>